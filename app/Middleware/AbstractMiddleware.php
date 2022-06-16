<?php
/**
 * @copyright 河南童伴童学网络科技
 * @link http://www.itbtx.cn
 * @time 2022-06-15 07:35
 * @author: lzf
 */

namespace App\Middleware;

use App\Helper\UrlRedirectorTrait;
use App\Traits\UserSessionTrait;
use App\Traits\ValidatorTrait;
use Hyperf\Contract\SessionInterface;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;
use Psr\Container\ContainerInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Guzzle\MiddlewareInterface;
use Hyperf\HttpServer\Contract\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

abstract class AbstractMiddleware implements MiddlewareInterface
{
    use ValidatorTrait;
    use UserSessionTrait;
    use UrlRedirectorTrait;
    
    #[Inject]
    protected ContainerInterface $container;
    #[Inject]
    protected RequestInterface $request;
    #[Inject]
    protected HttpResponse $response;

    public function getMiddleware(): callable {
        return function ($request, $handler) {
            return $this->process($request, $handler);
        };
    }

    /**
     * 中间件统一处理入口.
     */
    abstract public function process(ServerRequestInterface $request, RequestHandlerInterface $handler);
}