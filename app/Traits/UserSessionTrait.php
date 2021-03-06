<?php

namespace App\Traits;

use App\Exception\BusinessException;
use App\Exception\CASAuthException;
use App\Traits\ClientTrait;
use Hyperf\Contract\SessionInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;

trait UserSessionTrait
{
    use ClientTrait;
    
    #[Inject]
    protected SessionInterface $session;

    /**
     * 填充session.
     */
    public function fillSession(array $data) {}

    /**
     * 必须获取uid.
     * @return int
     */
    public function mustGetUid() {
        $uid = $this->session->get('uid');
        if (empty($uid)) {
            throw new CASAuthException("执行需要授权的操作,需要CAS授权");
        }
        return $uid;
    }
    /**
     * 尝试获取uid.
     * @return int
     */
    public function mayGetUid() {
        $uid = $this->session->get('uid');
        return $uid;
    }

    /**
     * 设置service_id.
     */
    public function setCacheServiceId($service_id) {
        $this->session->set('service_id', $service_id);
    }
    /**
     * 获取service_id.
     * @return int
     */
    public function mustGetCacheServiceId() {
        $service_id = $this->session->get('service_id');
        if (empty($service_id)) {
            throw new BusinessException("强制获取缓存的service_id失败");
        }
        return $service_id;
    }
    /**
     * 获取service_id,然后删除.
     * @return int
     */
    public function mustPullCacheServiceId() {
        $service_id = $this->session->get('service_id');
        $this->session->forget('service_id');
        if (empty($service_id)) {
            throw new BusinessException("强制拉取缓存的service_id失败");
        }
        return $service_id;
    }

    /**
     * 设置redirect_url.
     */
    public function setCacheRedirectUrl($redirect_url) {
        $this->session->set('redirect_url', $redirect_url);
    }
    /**
     * 获取redirect_url.
     * @return int
     */
    public function mustGetCacheRedirectUrl() {
        $redirect_url = $this->session->get('redirect_url');
        if (empty($redirect_url)) {
            throw new BusinessException("强制获取缓存的redirect_url失败");
        }
        return $redirect_url;
    }
    /**
     * 获取redirect_url.
     * @return int
     */
    public function mayGetCacheRedirectUrl() {
        $redirect_url = $this->session->get('redirect_url');
        if (empty($redirect_url)) {
            return $this->getClientIndexUrl();
        }
        return $redirect_url;
    }
    /**
     * 获取redirect_url,然后删除.
     * @return int
     */
    public function mustPullCacheRedirectUrl() {
        $redirect_url = $this->session->get('redirect_url');
        $this->session->forget('redirect_url');
        if (empty($redirect_url)) {
            throw new BusinessException("强制拉取缓存的redirect_url失败");
        }
        return $redirect_url;
    }
}