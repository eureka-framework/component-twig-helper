<?php

declare(strict_types=1);

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Component\TwigHelper\Middleware;

use Eureka\Component\TwigHelper\Service\TwigHelper;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\Routing\Router;
use Twig;

/**
 * Class TwigMiddleware
 *
 * @author Romain Cottard
 */
class TwigMiddleware implements MiddlewareInterface
{
    /** @var Twig\Environment */
    protected $router;

    /** @var Twig\Environment */
    protected $twig;

    /** @var Twig\Environment */
    protected $twigPaths;

    /** @var string $webAssetsPath */
    protected $webAssetsPath;

    /**
     * TwigMiddleware constructor.
     *
     * @param Router $router
     * @param Twig\Environment $twig
     * @param array $twigPaths
     * @param string $webAssetsPath
     */
    public function __construct(
        Router $router,
        Twig\Environment $twig,
        array $twigPaths = [],
        string $webAssetsPath = ''
    ) {
        $this->router        = $router;
        $this->twig          = $twig;
        $this->twigPaths     = $twigPaths;
        $this->webAssetsPath = $webAssetsPath;
    }

    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     *
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     * @throws
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->configurePaths();
        $this->configureFunctions();
        $this->configureExtensions();

        return $handler->handle($request);
    }

    /**
     * @return void
     * @throws Twig\Error\LoaderError
     */
    protected function configurePaths(): void
    {
        //~ Add path
        $loader = $this->twig->getLoader();
        if ($loader instanceof Twig\Loader\FilesystemLoader) {
            foreach ($this->twigPaths as $path => $namespace) {
                $loader->addPath($path, $namespace);
            }
        }
    }

    /**
     * @return void
     */
    protected function configureFunctions(): void
    {
        //~ Add functions to main twig instance
        $helper = new TwigHelper($this->router, $this->webAssetsPath);
        foreach ($helper->getCallbackFunctions() as $name => $callback) {
            $this->twig->addFunction(new Twig\TwigFunction($name, $callback));
        }
    }

    /**
     * @return void
     */
    protected function configureExtensions(): void
    {
    }
}
