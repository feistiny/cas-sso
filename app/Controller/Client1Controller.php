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

use App\Middleware\Client1AuthMiddleware;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Middleware;

#[AutoController]
class Client1Controller extends AbstractController
{
    /**
     * 不需要登录的页面.
     */
    public function no_auth_page() {
        return 'no_auth_page';
    }
    
    #[Middleware(Client1AuthMiddleware::class)]
    /**
     * 需要登录的页面.
     */
    public function auth_page() {
        return 'auth_page';
    }

}
