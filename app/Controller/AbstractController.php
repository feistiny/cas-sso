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
use App\Helper\UrlRedirectorTrait;
use App\Trait\UserSessionTrait;
use App\Trait\ValidatorTrait;
use Hyperf\Contract\SessionInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\View\RenderInterface;
use Psr\Container\ContainerInterface;

abstract class AbstractController
{
    use UserSessionTrait;
    use UrlRedirectorTrait;
    use ValidatorTrait;

    #[Inject]
    protected ContainerInterface $container;

    #[Inject]
    protected RequestInterface $request;

    #[Inject]
    protected ResponseInterface $response;
    
    #[Inject]
    protected SessionInterface $session;
    
    #[Inject]
    protected RenderInterface $render;

    protected function render($template, $data = [], $config = []) {
        return $this->render->render($template, $data, $config); 
    }

    protected function getBaseUrl() {
        throw new \LogicException("子类需要继承此方法"); 
    }
    protected function getSelfUrl($sub_url) {
        $base_url = $this->getBaseUrl();
        return "$base_url$sub_url";
    }
}
