<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Controller;

use App\Helper\AbstractUrlRedirector;
use App\Helper\UrlGetRedirector;
use App\Model\TsUser;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Contract\SessionInterface;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\View\RenderInterface;

#[AutoController]
class IndexController extends AbstractController
{
    public function url() {
        $urlRedirector = $this->getUrlRedirector();
        return $urlRedirector->redirect('http://101.43.82.144:9500', [
                'a' => 1,
                'c' => 3,
                'url' => 'http://asdfasdfasd.com/asdfas',
            ]); 
    }

    public function valid() {
        $this->validReq([
        ], [
            'a' => 'required',
        ], [], [
            'a' => 'a的字段名',
        ]);
    }

    public function db() {
        TsUser::create([
        ]);
        $users = TsUser::all();
        return $users;
    }

    public function view(RenderInterface $render) {
        return $render->render('test.tpl'); 
    }
}
