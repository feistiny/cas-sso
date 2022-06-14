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

use App\Helper\UrlGetRedirector;
use App\Helper\UrlPostRedirector;
use Hyperf\Contract\SessionInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Container\ContainerInterface;

abstract class AbstractController
{
    #[Inject]
    protected ContainerInterface $container;

    #[Inject]
    protected RequestInterface $request;

    #[Inject]
    protected ResponseInterface $response;
    
    #[Inject]
    protected SessionInterface $session;
    
    /**
     * 获取url重定向实例, 可根据请求参数返回不同的实现方式. 
     */
    protected function getRedirector() {
        $redirect_type = $this->request->input('redirect_type', 'get');
        $rtn = make(UrlGetRedirector::class);
        if ($redirect_type == 'post') {
            $rtn = make(UrlPostRedirector::class);
        }
        return $rtn;
    }
}
