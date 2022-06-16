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
use App\Exception\CASAuthException;
use App\Model\TsService;
use App\Model\TsServiceTicket;
use App\Model\TsTgt;
use App\Model\TsUser;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Contract\SessionInterface;
use Hyperf\HttpServer\Annotation\AutoController;

#[AutoController]
class ServerController extends AbstractController
{
    protected function getBaseUrl() {
        return "https://9500.lzf.itbtx.cn/";
    }

    /**
     * cas登录表单.
     */
    public function cas_login_form() {
        $data = $this->validReq($this->request->all(), [
            'redirect_url' => 'required',
            'service_id'   => 'required',
        ]);
        return $this->render('server_login.tpl', [
            'msg'          => $this->request->input('msg'),
            'redirect_url' => $data['redirect_url'],
            'service_id'   => $data['service_id'],
        ]);
    }
    /**
     * 注册/登录.
     */
    public function cas_auth() {
        $data = $this->validReq($this->request->all(), [
            'redirect_url' => 'required',
            'service_id'   => 'required',
        ]);
        if ($tgt_id = $this->session->get('tgt_id') && ! $this->request->input('is_cas_login')) {
            // 有全局会话
            // 其他应用来了, 直接登录
            $tgt = TsTgt::find($tgt_id);
            if (empty($tgt)) {
                throw new BusinessException("无效的全局回话");
            }
            $user = $tgt->user;
            $st = $this->getServiceTicket($tgt_id, $user->uid, $data['service_id']);
            if ($st->used <= 0) {
                return $this->getUrlRedirector()->redirect($this->getSelfUrl('server/cas_login_form'), [
                    'msg'          => "service ticket次数已用完, 请重新登录",
                    'redirect_url' => $data['redirect_url'],
                    'service_id'   => $data['service_id'],
                ]);
            }
            $table = $st->getTable();
            Db::update("update $table set used=used-1 where used>0 and st_id={$st->st_id};");
        } else {
            if ($this->request->input('is_cas_login')) {
                $user_data = $this->validReq($this->request->all(), [
                    'username' => 'required',
                    'password' => 'required',
                ]);
            } else {
                return $this->getUrlRedirector()->redirect($this->getSelfUrl('server/cas_login_form'), [
                    'msg'          => $this->request->input('msg'),
                    'redirect_url' => $data['redirect_url'],
                    'service_id'   => $data['service_id'],
                ]);
            }

            $user = $this->createOrCheckUser($user_data['username'], $user_data['password']);
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

            $st = $this->getServiceTicket($tgt->tgt_id, $user->uid, $data['service_id']);
            $st->update([
                'used' => $this->getCasMaxUseNum(),
            ]);
        }

        $service = TsService::findOrFail($data['service_id']);

        $this->session->set('tgt_id', $tgt->tgt_id);

        return $this->getUrlRedirector()->redirect($service->url, [
            'st'           => $st->st_id,
            'redirect_url' => $data['redirect_url'],
        ]);
    }
    /**
     *
     */
    protected function createOrCheckUser($username, $password) {
        $user = TsUser::where('username', $username)->first();
        if (empty($user)) {
            var_dump($username);
            $user = TsUser::create([
                'username' => $username,
                'password' => $password,
            ]);
        } else {
            if ($user->password != $password) {
                throw new \App\Exception\BusinessException("账号密码不对");
            }
        }
        return $user;
    }
    /**
     * 获取service ticket.
     * @return TsServiceTicket
     */
    private function getServiceTicket($tgt_id, $uid, $service_id) {
        $st = TsServiceTicket::firstOrCreate([
            'tgt_id'     => $tgt_id,
            'uid'        => $uid,
            'service_id' => $service_id,
        ], [
            'used'       => $this->getCasMaxUseNum(),
            'expires_in' => date('Y-m-d H:i:s', strtotime('+3 day')),
            'validate'   => TsServiceTicket::VALIDATE_YES,
        ]);
        return $st;
    }
    /**
     * 获取最大认证次数.
     * @return int
     */
    private function getCasMaxUseNum() {
        return config('cas.cas_max_used_num', 3);
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
        ])->first();
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
        $data = $this->validReq($this->request->all(), [
            'service_id' => 'required',
        ]);
        $tgt_id = $this->session->get('tgt_id');
        if (empty($tgt_id)) {
            throw new BusinessException("退出登录缺少凭证");
        }
        $tgt = TsTgt::findOrFail($tgt_id);
        $st_list = $tgt->st;
        $rtn[] = '清除所有client的登录状态';
        foreach ($st_list as $st) {
            $service = $st->service;
            $rtn[] = "client $service->service_id cas剩余次数 $st->used/" . $this->getCasMaxUseNum();
            if ($st->service_id == $data['service_id']) {
                $rtn[] = "client $service->service_id session 是自己负责清除的";
                // 主动退出的客户端, session已在请求的时候清除过了.
                continue;
            }
            $ok = $this->logout_service($service->logout_url, $st->st_id);
            $rtn[] = "client $service->service_id session 清除结果 $ok";
        }
        $rtn[] = "1成功, 0失败";
        $rtn[] = "请手动返回";
        return $this->response->raw(implode("\n", $rtn));
    }

    /**
     * 退出各个客户端的service.
     * @param $logout_url
     * @param $st
     * @return bool
     */
    private function logout_service($logout_url, $st) {
        try {
            $res = file_get_contents($logout_url . "?st=$st");
            if (empty($res)) {
                return true;
            }
        } catch (\Throwable $e) {
        }
        return false;
    }
}
