<?php
/**
 * @copyright 河南童伴童学网络科技
 * @link http://www.itbtx.cn
 * @time 2022-06-15 00:02
 * @author: lzf
 */

namespace App\Helper;

class UrlGetRedirector extends AbstractUrlRedirector
{
    /**
     * 直接通过url的get方式跳转, 重要参数推荐用post的表单方法.
     * @param $url
     * @param array $data
     * @return \Hyperf\HttpServer\Response
     */
    public function redirect($url, $data = []) {
        $redirect_url = $url;
        var_dump(func_get_args());
        if (! empty($data)) {
            $char = strpos($url, '?') === false ? '?' : '&';
            $redirect_url = $url . $char . http_build_query($data);
        }
        return $this->response->redirect($redirect_url);
    }
}