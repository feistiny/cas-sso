<?php
/**
 * @copyright 河南童伴童学网络科技
 * @link http://www.itbtx.cn
 * @time 2022-06-15 00:02
 * @author: lzf
 */

namespace App\Helper;

use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\HttpServer\Response;
use Hyperf\View\RenderInterface;

class UrlPostRedirector extends AbstractUrlRedirector
{
    /**
     * 通过返回html表单,js用post提交form,不暴露参数.
     * @return Response
     */
    public function redirect($url, $data = []) {
        return make(RenderInterface::class)->render('redirect_form.tpl', [
            'url' => $url,
            'data' => $data,
        ]);
    }
}