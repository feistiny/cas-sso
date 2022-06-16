<?php
/**
 * @copyright 河南童伴童学网络科技
 * @link http://www.itbtx.cn
 * @time 2022-06-15 00:00
 * @author: lzf
 */

namespace App\Helper;

use App\Trait\UserSessionTrait;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;

/**
 * url跳转类.
 */
abstract class AbstractUrlRedirector
{
    use UserSessionTrait;

    #[Inject]
    protected ResponseInterface $response;
    #[Inject]
    protected RequestInterface $request;

    /**
     * 携带参数跳转到对应的地址.
     * @return \Hyperf\HttpServer\Response
     */
    abstract public function redirect($url, $data = []);

    /**
     * CAS认证跳转.
     * @return \Hyperf\HttpServer\Response
     */
    public function CASRedirect() {
        $url = config('cas.cas_auto_login_url');
        $service_id = $this->mustGetCacheServiceId();
        $redirect_url = $this->mustGetCacheRedirectUrl();
        $auth_data['redirect_url'] = $redirect_url;
        $auth_data['service_id'] = $service_id;
        return $this->redirect($url, $auth_data);
    }

    /**
     * 获取
     */
    public function get() {
    }
}