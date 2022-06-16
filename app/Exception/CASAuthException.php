<?php
/**
 * @author 河南童伴童学网络科技
 * @link http://www.itbtx.cn
 * User: lzf
 * Date: 2022/6/15
 * Time: 10:12 AM
 */
namespace App\Exception;

class CASAuthException extends BusinessException
{
    private $server_id;
    /**
     * 设置service_id.
     * @return self
     */
    public function withServiceId($service_id) {
        $this->server_id = $service_id;
        return $this;
    }
    /**
     * 获取service_id.
     */
    public function getServiceId() {
        return $this->server_id;
    }
}