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

use App\Middleware\ClientAuthMiddleware;
use App\Model\Tc1Info;
use App\Model\Tc1ServiceTicket;
use App\Model\Tc2Info;
use App\Trait\ValidatorTrait;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;

#[Controller]
class Client1Controller extends ClientController
{
    protected $__service_id = 1;
    
    protected function getBaseUrl() {
        return 'https://9501.lzf.itbtx.cn/client1/';
    }
    protected function getClientTitle() {
        return 'client1';
    }

    #[Middleware(ClientAuthMiddleware::class)]
    #[RequestMapping(path: "auth_page", methods: "get")]
    /**
     * 需要登录的页面.
     */
    public function auth_page() {
        return $this->do_auth_page();
    }

    protected function cas_back_saveinfo($info) {
        Tc1Info::updateOrCreate([
            'info_id' => $info['uid'],
        ], [
            'username' => $info['username'],
        ]);
        Tc1ServiceTicket::updateOrCreate([
            'st_id' => $info['st'],
        ], [
            'validate'   => 1,
            'session_id' => $this->session->getId(),
        ]);
    }

    /**
     * 异步退出登录.
     */
    public function async_logout() {
    }
}
