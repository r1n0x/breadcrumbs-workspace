<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Internal\Generator;

use R1n0x\BreadcrumbsBundle\Exception\UndefinedParameterException;
use R1n0x\BreadcrumbsBundle\Internal\Model\BreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\RootBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\RouteBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Provider\UrlParametersProvider;
use Symfony\Component\Routing\RouterInterface;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
final readonly class UrlGenerator
{
    public function __construct(
        private RouterInterface $router,
        private UrlParametersProvider $provider
    ) {}

    /**
     * @throws UndefinedParameterException
     */
    public function generate(BreadcrumbDefinition $definition): ?string
    {
        $routeName = $definition->getRouteName();
        if ($definition instanceof RootBreadcrumbDefinition && null === $routeName) {
            return null;
        }

        return match (true) {
            $definition instanceof RootBreadcrumbDefinition => $this->router->generate($routeName),
            $definition instanceof RouteBreadcrumbDefinition => $this->router->generate(
                $definition->getRouteName(),
                $this->provider->getParameters($definition)
            )
        };
    }
}
