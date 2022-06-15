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

use Hyperf\Di\Annotation\Inject;
use Hyperf\Contract\SessionInterface;
use Hyperf\HttpServer\Annotation\AutoController;

#[AutoController]
class ServerController extends AbstractController
{
    /**
     * 注册/登录.
     */
    public function cas_auth() {
        $data = ValidatorTrait::validReq($this->request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);
        return '123';
        return [];
    }
    /**
     * 退出登录.
     */
}
