<?php

namespace App\Trait;

use App\Exception\CASAuthException;
use Hyperf\Contract\SessionInterface;
use Hyperf\Di\Annotation\Inject;

trait UserSessionTrait
{
    #[Inject]
    protected SessionInterface $session;

    /**
     * 填充session.
     */
    public function fillSession(array $data) {}

    /**
     * 获取uid.
     * @return int
     */
    public function getUid() {
        $uid = $this->session->get('uid');
        if (empty($uid)) {
            throw new CASAuthException("CAS没有授权");
        }
        return $uid;
    }
}