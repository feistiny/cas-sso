<?php
/**
 * @author 河南童伴童学网络科技
 * @link http://www.itbtx.cn
 * User: lzf
 * Date: 2022/6/15
 * Time: 12:49 PM
 */
namespace App\Helper;

use Hyperf\HttpServer\Contract\RequestInterface;

trait UrlRedirectorTrait
{
    /**
     * 获取url重定向实例, 可根据请求参数返回不同的实现方式.
     */
    protected function getUrlRedirector() {
        $redirect_type = $this->request->input('redirect_type', 'get');
        $rtn = make(UrlGetRedirector::class);
        if ($redirect_type == 'post') {
            $rtn = make(UrlPostRedirector::class);
        }
        return $rtn;
    }
}