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

class UrlPostRedirector extends AbstractUrlRedirector
{
    /**
     * 通过返回html表单,js用post提交form,不暴露参数.
     * @return Response
     */
    public function redirect($url, $data = []) {
        $input_str = '';
        foreach ($data as $name => $value) {
            $input_str .= <<<HTML
<input type='hidden' name='{$name}' value='{$value}'>
HTML;
        }

        $html = <<<HTML
<html>
<body>
<form name="submitForm" action="$url" method="post">
{$input_str}
</form>
</body>
<script>window.document.submitForm.submit();</script>
</html>
HTML;

        $response = $this->response
            ->withAddedHeader('content-type', 'text/html; charset=utf-8')
            ->withBody(new SwooleStream($html));
        return $response;
    }
}