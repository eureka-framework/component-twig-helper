<?php

declare(strict_types=1);

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Component\TwigHelper\Traits;

use Eureka\Component\TwigHelper\Context;
use Twig\Environment;

/**
 * Trait TwigControllerAwareTrait
 *
 * @author Romain Cottard
 */
trait TwigControllerAwareTrait
{
    /** @var Environment */
    private $twig;

    /** @var Context $context Data collection object. */
    protected $context = null;

    /**
     * @param Environment $twig
     * @return void
     */
    public function setTwig(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * Get context.
     *
     * @return Context
     */
    protected function getContext()
    {
        if ($this->context === null) {
            $this->context = new Context();
        }

        return $this->context;
    }

    /**
     * @param string $name
     * @return string
     * @throws
     */
    protected function render(string $name): string
    {
        $template = $this->twig->load($name);

        return $template->render($this->getContext()->toArray());
    }
}
