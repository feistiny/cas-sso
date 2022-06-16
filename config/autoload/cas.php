<?php
/**
 * @author 河南童伴童学网络科技
 * @link http://www.itbtx.cn
 * User: lzf
 * Date: 2022/6/15
 * Time: 12:53 PM
 */

use App\Model\Tc1ServiceTicket;
use App\Model\Tc2ServiceTicket;

$client1_service_id = 1;
$client2_service_id = 2;
$host_to_service_id = [
    '9501.lzf.itbtx.cn' => $client1_service_id,
    '9502.lzf.itbtx.cn' => $client2_service_id,
];
$client_index_url = [
    $client1_service_id => 'https://9501.lzf.itbtx.cn/client1/index',
    $client2_service_id => 'https://9502.lzf.itbtx.cn/client2/index',
];
$client_base_url_map = [
    $client1_service_id => 'https://9501.lzf.itbtx.cn/client1/',
    $client2_service_id => 'https://9502.lzf.itbtx.cn/client2/',
];
$client_service_ticket_class_map = [
    $client1_service_id => Tc1ServiceTicket::class,
    $client2_service_id => Tc2ServiceTicket::class,
];
return [
    'cas_max_used_num'                => 1, // service ticket 最大次数
    'cas_auto_login_url'              => 'https://9500.lzf.itbtx.cn/server/cas_auth',
    'cas_userinfo_url'                => 'https://9500.lzf.itbtx.cn/server/user_info',
    'cas_logout_url'                  => 'https://9500.lzf.itbtx.cn/server/cas_logout',
    'host_to_server_id'               => $host_to_service_id,
    'client_index_url'                => $client_index_url,
    'client_base_url_map'             => $client_base_url_map,
    'client_service_ticket_class_map' => $client_service_ticket_class_map,
];