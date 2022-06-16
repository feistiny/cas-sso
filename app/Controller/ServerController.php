<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Controller;

use App\Exception\BusinessException;
use App\Model\TsService;
use App\Model\TsServiceTicket;
use App\Model\TsTgt;
use App\Model\TsUser;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Contract\SessionInterface;
use Hyperf\HttpServer\Annotation\AutoController;

#[AutoController]
class ServerController extends AbstractController
{
    protected function getBaseUrl() {
        return "https://9500.lzf.itbtx.cn/";
    }

    private $__service_id = 0;
    private function getServiceId() {
        $service_id = $this->__service_id;
        if (empty($service_id)) {
            throw new \App\Exception\BusinessException("service_id强制获取失败");
        }
        return $service_id;
    }
    private function setServiceId($service_id) {
        if (empty($service_id)) {
            throw new BusinessException("不能设置空的service_id");
        }
        $this->__service_id = $service_id;
    }
    private $__redirect_url;
    private function getRedirectUrl() {
        $redirect_url = $this->__redirect_url;
        if (empty($redirect_url)) {
            throw new \App\Exception\BusinessException("redirect_url强制获取失败");
        }
        return $redirect_url;
    }
    private function setRedirectUrl($redirect_url) {
        if (empty($redirect_url)) {
            throw new BusinessException("不能设置空的redirect_url");
        }
        $this->__redirect_url = $redirect_url;
    }

    public function cas_login_form() {
        $this->mustGetCacheRedirectUrl();
        $this->mustGetCacheServiceId();
        return $this->render('server_login.tpl');
    }
    /**
     * 注册/登录.
     */
    public function cas_auth() {
        if ($tgt_id = $this->session->get('tgt_id')) {
            $data = $this->validReq($this->request->all(), [
                'redirect_url' => 'required',
                'service_id'   => 'required',
            ]);
            // 这里应该从浏览器获取地址
            $this->setRedirectUrl($data['redirect_url']);
            $this->setServiceId($data['service_id']);
            // 有全局会话
            // 其他应用来了, 直接登录
            $tgt = TsTgt::find($tgt_id);
            if (empty($tgt)) {
                throw new BusinessException("无效的全局回话");
            }
            $user = $tgt->user;
        } else {
            if (empty($this->request->input('username'))) {
                $data = $this->validReq($this->request->all(), [
                    'redirect_url' => 'required',
                    'service_id'   => 'required',
                ]);
                $this->setCacheServiceId($data['service_id']);
                $this->setCacheRedirectUrl($data['redirect_url']);
                return $this->getUrlRedirector()->redirect($this->getSelfUrl('server/cas_login_form'));
            } else {
                $this->setServiceId($this->mustPullCacheServiceId());
                $this->setRedirectUrl($this->mustPullCacheRedirectUrl());
            }

            $data = $this->validReq($this->request->all(), [
                'username' => 'required',
                'password' => 'required',
            ]);
            $user = TsUser::updateOrCreate([
                'username' => $data['username'],
            ], [
                'password' => $data['password'],
            ]);
            if (empty($user)) {
                throw new BusinessException("用户不存在");
            }
            $tgt = TsTgt::updateOrCreate([
                'uid' => $user->uid,
            ], [
                'expires_in' => date('Y-m-d H:i:s', strtotime('+7 day')),
                'validate'   => TsTgt::VALIDATE_YES,
            ]);
            if (empty($tgt)) {
                throw new BusinessException("全局令牌创建失败");
            }
        }

        $service = TsService::findOrFail($this->getServiceId());
        $st = TsServiceTicket::updateOrCreate([
            'tgt_id'     => $tgt->tgt_id,
            'uid'        => $user->uid,
            'service_id' => $service->service_id,
        ], [
            'used'       => 2, // 重置次数
            'expires_in' => date('Y-m-d H:i:s', strtotime('+3 day')),
            'validate'   => TsServiceTicket::VALIDATE_YES,
        ]);

        $this->session->set('tgt_id', $tgt->tgt_id);

        return $this->getUrlRedirector()->redirect($service->url, [
            'st'           => $st->st_id,
            'redirect_url' => $this->getRedirectUrl(),
        ]);
    }
    /**
     * 用户信息.
     */
    public function user_info() {
        $data = $this->validReq($this->request->all(), [
            'st' => 'required',
        ]);
        $st = TsServiceTicket::where([
            'st_id' => $data['st'],
        ])
            ->where('used', '>', 0)
            ->first();
        if (empty($st)) {
            throw new BusinessException("凭证已失效");
        }
        $st->decrement('used');
        $user = TsUser::findOrFail($st->uid);
        return [
            'uid'      => $user->uid,
            'username' => $user->username,
        ];
    }
    /**
     * 退出登录.
     */
    public function cas_logout() {
        $tgt_id = $this->session->get('tgt_id');
        if (empty($tgt_id)) {
            throw new BusinessException("退出登录缺少凭证"); 
        }
        $tgt = TsTgt::findOrFail($tgt_id);
        $services = TsService::where('uid', $tgt->uid)->get();
        foreach ($services as $service) {
            file_get_contents($service->logout_url."?uid={$service->uid}");
        }
    }
}
