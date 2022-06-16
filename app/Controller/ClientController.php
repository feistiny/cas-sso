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

use App\Middleware\ClientAuthMiddleware;
use App\Model\Tc1Info;
use App\Model\Tc1ServiceTicket;
use App\Trait\ValidatorTrait;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;

abstract class ClientController extends AbstractController
{
    protected function mustGetServiceId() {
        return $this->__service_id; 
    }
    
    #[RequestMapping(path: "index", methods: "get")]
    public function index() {
        if ($uid = $this->mayGetUid()) {
            $user = Tc1Info::findOrFail($uid);
            $username = $user->username;
        }
        $this->setCacheRedirectUrl($this->getSelfUrl('index'));
        $this->setCacheServiceId($this->mustGetServiceId());
        return $this->render('client_index.tpl', [
            'title'        => $this->getClientTitle().'的首页',
            'service_id'   => $this->mustGetServiceId(),
            'username'     => $username ?: '',
            'redirect_url' => $this->getSelfUrl("index"),
        ]);
    }

    #[Middleware(ClientAuthMiddleware::class)]
    /**
     * 需要登录的页面.
     */
    public function do_auth_page() {
        $uid = $this->mustGetUid();
        return $this->response->raw("cas授权后, {$this->getClientTitle()}上操作成功! 返回请手动回退.");
    }

    /**
     * 需要授权才能访问的页面.
     */
    abstract public function auth_page();

    #[RequestMapping(path: "cas_back", methods: "get,post")]
    /**
     * cas返回时,统一
     */
    public function cas_back() {
        $data = $this->validReq($this->request->all(), [
            'st' => 'required',
        ]);
        $info = $this->get_cas_userinfo($data['st']);
        $this->session->set('uid', $info['uid']);
        $this->cas_back_saveinfo([
            'uid'      => $info['uid'],
            'username' => $info['username'],
            'st'       => $data['st'],
        ]);
        // 保存用户信息
        return $this->getUrlRedirector()->redirect($this->mustGetCacheRedirectUrl());
    }
    /**
     * 保存cas的授权用户信息.
     */
    protected function cas_back_saveinfo($info) {
        throw new \App\Exception\BusinessException("客户端需要保存cas授权信息");
    }

    /**
     * 异步退出登录.
     */
    public function async_logout() {}

    /**
     * 退出登录.
     */
    public function logout() {
        $this->session->clear();
        return $this->getUrlRedirector()->redirect(config('cas.cas_logout_url'));
    }
    /**
     * 根据st去cas换取用户信息.
     */
    private function get_cas_userinfo($st) {
        $cas_url = config('cas.cas_userinfo_url');
        $res = file_get_contents($cas_url."?service_id={$this->mustGetServiceId()}&st=$st");
        $user_info = json_decode($res, true);
        return $user_info;
    }
}
