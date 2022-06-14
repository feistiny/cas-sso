<?php
/**
 * @copyright 河南童伴童学网络科技
 * @link http://www.itbtx.cn
 * @time 2022-06-15 00:00
 * @author: lzf
 */

namespace App\Helper;

/**
 * url跳转类.
 */
abstract class AbstractUrlRedirector
{
    /**
     * 携带参数跳转到对应的地址.
     */
    abstract public function urlRedirect($url, $data = []);
}