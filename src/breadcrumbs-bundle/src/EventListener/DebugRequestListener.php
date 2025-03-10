<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\EventListener;

use R1n0x\BreadcrumbsBundle\CacheWarmer\BreadcrumbsCacheWarmer;
use R1n0x\BreadcrumbsBundle\Exception\InvalidConfigurationException;
use R1n0x\BreadcrumbsBundle\Exception\RouteValidationException;
use R1n0x\BreadcrumbsBundle\Exception\UnknownRootException;
use R1n0x\BreadcrumbsBundle\Exception\UnknownRouteException;
use R1n0x\BreadcrumbsBundle\Exception\VariablesResolverException;
use Symfony\Component\HttpKernel\Event\RequestEvent;

/**
 * @codeCoverageIgnore
 *
 * @author r1n0x <r1n0x-dev@proton.me>
 */
final readonly class DebugRequestListener
{
    public function __construct(
        private BreadcrumbsCacheWarmer $warmer,
        private string $cacheDir,
    ) {}

    /**
     * @throws InvalidConfigurationException
     * @throws RouteValidationException
     * @throws UnknownRouteException
     * @throws UnknownRootException
     * @throws VariablesResolverException
     */
    public function __invoke(RequestEvent $event): void
    {
        $this->warmer->warmUp($this->cacheDir);
    }
}
