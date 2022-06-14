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
use Hyperf\Di\Annotation\Inject;
use Hyperf\Contract\SessionInterface;
use Hyperf\HttpServer\Annotation\AutoController;

#[AutoController]
class IndexController extends AbstractController
{
    public function index() {
        $user = $this->request->input('user', 'Hyperf');
        $method = $this->request->getMethod();

        return [
            'input' => $this->request->all(),
            'session' => $this->session->all(),
            'method'  => $method,
            'message' => "Hello2 {$user}.",
        ];
    }

    public function url() {
        $urlRedirector = $this->getRedirector();
        return $urlRedirector->urlRedirect('http://101.43.82.144:9500', [
                'a' => 1,
                'c' => 3,
                'url' => 'http://asdfasdfasd.com/asdfas',
            ]); 
    }
}
