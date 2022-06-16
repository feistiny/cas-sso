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
    /**
     * @inheritDoc
     */
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
}
