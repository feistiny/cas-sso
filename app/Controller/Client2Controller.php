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
use App\Model\Tc2ServiceTicket;
use App\Trait\ValidatorTrait;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;

#[AutoController]
class Client2Controller extends ClientController
{
    protected $service_id = 2;

    protected function getBaseUrl() {
        return 'https://9502.lzf.itbtx.cn/client2/';
    }
    protected function getClientTitle() {
        return 'client2';
    }

    #[Middleware(ClientAuthMiddleware::class)]
    /**
     * 需要登录的页面.
     */
    public function auth_page() {
        return $this->do_auth_page();
    }

    protected function cas_back_saveinfo($info) {
        Tc2Info::updateOrCreate([
            'info_id' => $info['uid'],
        ], [
            'username' => $info['username'],
        ]);
        Tc2ServiceTicket::updateOrCreate([
            'st_id' => $info['st'],
        ], [
            'validate'   => 1,
            'session_id' => $this->session->getId(),
        ]);
    }
}
