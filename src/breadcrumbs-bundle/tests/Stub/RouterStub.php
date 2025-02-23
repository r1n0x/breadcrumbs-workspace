<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Stub;

use R1n0x\BreadcrumbsBundle\Exception\RuntimeException;
use R1n0x\BreadcrumbsBundle\Internal\Resolver\ParametersResolver;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RouterInterface;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class RouterStub implements RouterInterface
{
    private ParametersResolver $resolver;

    /** @var array<string, string> */
    private array $routes = [];

    public function __construct()
    {
        $this->resolver = new ParametersResolver();
    }

    public function setContext(RequestContext $context): void {}

    public function getContext(): RequestContext
    {
        return new RequestContext();
    }

    public function getRouteCollection(): RouteCollection
    {
        return new RouteCollection();
    }

    public function addRouteStub(string $name, string $path): void
    {
        $this->routes[$name] = $path;
    }

    public function generate(string $name, array $parameters = [], int $referenceType = self::ABSOLUTE_PATH): string
    {
        $path = $this->routes[$name] ?? throw new RuntimeException(sprintf('ROUTE "%s" WAS NOT ADDED TO ROUTER STUB', $name));

        foreach ($this->resolver->getParameters($path) as $parameterName) {
            $path = str_replace(sprintf('{%s}', $parameterName), $parameters[$parameterName], $path);
        }

        return $path;
    }

    public function match(string $pathinfo): array
    {
        return [];
    }
}
