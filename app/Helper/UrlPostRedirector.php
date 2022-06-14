<?php
/**
 * @copyright 河南童伴童学网络科技
 * @link http://www.itbtx.cn
 * @time 2022-06-15 00:02
 * @author: lzf
 */

namespace App\Helper;

class UrlPostRedirector extends AbstractUrlRedirector
{
    /**
     * 通过返回html表单,js用post提交form,不暴露参数.
     */
    public function urlRedirect($url, $data = []) {
        $input_str = '';
        foreach ($data as $name => $value) {
            $input_str .= <<<HTML
<input type='hidden' name='{$name}' value='{$value}'>
HTML;
        }

        $html = <<<HTML
<html>
<body onload='document.forms["form"].submit()'>
<form>
{$input_str}
</form>
</body>
</html>
HTML;
        
        return $html;
    }
}