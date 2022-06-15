<?php
/**
 * @copyright 河南童伴童学网络科技
 * @link http://www.itbtx.cn
 * @time 2022-06-15 00:00
 * @author: lzf
 */

namespace App\Helper;

use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;

/**
 * url跳转类.
 */
abstract class AbstractUrlRedirector
{
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
        $username = $this->request->input('username');
        $password = $this->request->input('password');
        $auth_data = [];
        if (! empty($username)) {
            $auth_data['username'] = $username;
        }
        if (! empty($password)) {
            $auth_data['password'] = $password;
        }
        return $this->redirect($url, $auth_data);
    }
}