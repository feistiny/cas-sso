<?php
/**
 * @copyright 河南童伴童学网络科技
 * @link http://www.itbtx.cn
 * @time 2022-06-15 07:36
 * @author: lzf
 */

namespace App\Middleware;

use App\Exception\CASAuthException;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Client1AuthMiddleware extends AbstractMiddleware
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) {
        try {
            return $handler->handle($request);
        } catch (CASAuthException $e) {
            $redirector = $this->getUrlRedirector();
            return $redirector->CASRedirect();
        }
    }
}