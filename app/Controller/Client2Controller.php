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
use App\Model\Tc2Info;
use App\Trait\ValidatorTrait;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;

#[AutoController]
class Client2Controller extends ClientController
{
    protected $service_id = 2;

    #[Middleware(Client1AuthMiddleware::class)]
    /**
     * 需要登录的页面.
     */
    public function auth_page() {
        return [
            'auth_page',
            'uid' => $this->getUid(),
        ];
    }
    /**
     * cas返回时,统一
     */
    public function cas_back() {
        $data = $this->validReq($this->request->all(), [
            'st'           => 'required',
            'redirect_url' => 'required',
        ]);
        $user_info = $this->get_cas_userinfo($data['st']);
        $this->session->set('uid', $user_info['uid']);
        Tc2Info::updateOrCreate([
            'info_id'    => $user_info['uid'],
        ], [
            'username'   => $user_info['username'],
            'session_id' => $this->session->getId(),
        ]);;
        // 保存用户信息
        return $this->getUrlRedirector()->redirect($data['redirect_url']);
    }

    /**
     * 退出登录.
     */
    public function logout() {
        $this->session->clear();
        file_get_contents(config('cas.cas_userinfo_url') . "?service_id={$this->service_id}");
        return $this->getUrlRedirector()->redirect('/');
    }
    /**
     * 根据st去cas换取用户信息.
     */
    private function get_cas_userinfo($st) {
        $cas_url = config('cas.cas_userinfo_url');
        $res = file_get_contents($cas_url . "?service_id={$this->service_id}&st=$st");
        $user_info = json_decode($res, true);
        return $user_info;
    }
}
