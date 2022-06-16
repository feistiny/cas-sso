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
use App\Model\Tc1Info;
use App\Model\Tc1ServiceTicket;
use App\Model\Tc2Info;
use App\Trait\ValidatorTrait;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;

#[AutoController]
class Client1Controller extends AbstractController
{
    protected $service_id = 1;

    protected function getBaseUrl() {
        return 'https://9501.lzf.itbtx.cn/';
    }

    public function index() {
        if ($uid = $this->mayGetUid()) {
            $user = Tc1Info::findOrFail($uid);
            $username = $user->username;
        }
        return $this->render('client_index.tpl', [
            'title'        => 'client1的首页',
            'service_id'   => $this->service_id,
            'username'     => $username,
            'redirect_url' => $this->getSelfUrl("client1/index"),
        ]);
    }

    #[Middleware(Client1AuthMiddleware::class)]
    /**
     * 需要登录的页面.
     */
    public function auth_page() {
        $uid = $this->mustGetUid();
        return ["授权后, 操作成功!"];
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
        Tc1Info::updateOrCreate([
            'info_id' => $user_info['uid'],
        ], [
            'username' => $user_info['username'],
        ]);
        Tc1ServiceTicket::updateOrCreate([
            'st_id' => $data['st'],
        ], [
            'validate'   => 1,
            'session_id' => $this->session->getId(),
        ]);
        // 保存用户信息
        return $this->getUrlRedirector()->redirect($data['redirect_url']);
    }


    /**
     * 异步退出登录.
     */
    public function async_logout() {
    }

    /**
     * 退出登录.
     */
    public function logout() {
        $this->session->clear();
        return $this->getUrlRedirector()->redirect(config('cas.cas_logout_url'));
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
