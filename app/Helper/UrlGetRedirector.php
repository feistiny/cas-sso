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
    public function urlRedirect($url, $data = []) {
        $char = strpos($url, '?') === false ? '?' : '&';
        return $url . $char . http_build_query($data);
    }
}