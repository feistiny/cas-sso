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
use App\Traits\ClientTrait;
use App\Traits\ValidatorTrait;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;

abstract class ClientController extends AbstractController
{
    use ClientTrait;

    protected function getClientTitle() {
        return 'client' . $this->getServiceIdByDomain();
    }
    protected function getBaseUrl() {
        return $this->getClientMapValueByKey('client_base_url_map');
    }
    protected function mustGetServiceId() {
        return $this->getServiceIdByDomain();
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
            'username'     => $username ?? '',
            'redirect_url' => $this->getSelfUrl("index"),
        ]);
    }
    /**
     * 根据st去cas换取用户信息.
     */
    private function get_cas_userinfo($st) {
        $cas_url = config('cas.cas_userinfo_url');
        return $this->rpc_request($cas_url."?service_id={$this->mustGetServiceId()}&st=$st");
    }

    #[Middleware(ClientAuthMiddleware::class)]
    #[RequestMapping(path: "auth_page", methods: "get")]
    /**
     * 需要登录的页面.
     */
    public function auth_page() {
        $uid = $this->mustGetUid();
        return $this->render('info.tpl', [
            'infos' => [
                "cas授权后, {$this->getClientTitle()}上操作成功! 返回请手动回退." 
            ],
        ]);
    }

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
        return $this->getUrlRedirector()->redirect($this->mayGetCacheRedirectUrl());
    }
    /**
     * 保存cas的授权用户信息.
     * @param $info
     */
    abstract protected function cas_back_saveinfo($info);

    #[RequestMapping(path: "async_logout", methods: "get,post")]
    /**
     * 异步退出登录.
     */
    public function async_logout() {
        $data = $this->validReq($this->request->all(), [
            'st' => 'required',
        ]);
        $session_id = $this->getSessionIdByST($data['st']);
        $this->session->setId($session_id);
        $this->clearCurrentSession($this->session);
    }
    /**
     * 根据st获取对应的session_id.
     * @param $st_id
     * @return mixed
     */
    protected function getSessionIdByST($st_id) {
        $service_ticket_class = $this->getClientMapValueByKey('client_service_ticket_class_map');
        $st = $service_ticket_class::find($st_id);
        if (empty($st)) {
            throw new \APP\Exception\BusinessException("没有找到session_id,st_id: $st_id");
        }
        $session_id = $st->session_id;
        if (empty($session_id)) {
            throw new \APP\Exception\BusinessException("getSessionIdByST session_id is empty, st_id: $st_id");
        }
        return $session_id;
    }
    #[RequestMapping(path: "logout", methods: "get")]
    public function logout() {
        $this->clearCurrentSession($this->session);
        return $this->getUrlRedirector()->redirect(config('cas.cas_logout_url'), [
            'service_id' => $this->getServiceIdByDomain(),
        ]);
    }
    /**
     * 根据session_id清楚session.
     * @param \Hyperf\Contract\SessionInterface $session
     */
    protected function clearCurrentSession($session) {
        $bool = $session->invalidate();
        if (empty($bool)) {
            throw new \App\Exception\BusinessException("session清除失败, session_id: " . $session->getId());
        }
    }
}
