<?php
/**
 * @copyright 河南童伴童学网络科技
 * @link http://www.itbtx.cn
 * @time 2022-06-16 21:11
 * @author: lzf
 */

namespace App\Traits;


use App\Exception\BusinessException;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use function config;

trait ClientTrait
{

    #[Inject]
    protected RequestInterface $request;

    /**
     * 获取service_id.
     */
    protected function getServiceIdByDomain() {
        $full_url = $this->request->fullUrl();
        $arr = parse_url($full_url);
        $current_host = $arr['host'];
        $host_to_service_id_map = config('cas.host_to_server_id');
        foreach ($host_to_service_id_map as $host => $service_id) {
            if ($host == $current_host) {
                return $service_id;
            }
        }
        throw new BusinessException("从host获取service_id失败,当前host为: $current_host");
    }

    /**
     * 获取当前客户端的首页地址.
     */
    protected function getClientIndexUrl() {
        $service_id = $this->getServiceIdByDomain();
        $index_url = config('cas.client_index_url')[$service_id];
        if (empty($index_url)) {
            throw new \APP\Exception\BusinessException("getServiceIdByDomain err by service_id $service_id");
        }
        return $index_url;
    }

    /**
     * 根据key获取cas的配置信息.
     * @param $key
     * @return mixed
     */
    protected function getClientMapValueByKey($key) {
        $service_id = $this->getServiceIdByDomain();
        $map = config("cas.$key");
        if (empty($map)) {
            throw new \APP\Exception\BusinessException("根据key获取cas的配置信息失败,key对应的数据不存在:$key");
        }
        $value = $map[$service_id];
        if (empty($value)) {
            throw new \APP\Exception\BusinessException("根据key获取cas的配置信息失败,key对应的数据,没有找到service_id: $service_id");
        }
        return $value;
    }
}