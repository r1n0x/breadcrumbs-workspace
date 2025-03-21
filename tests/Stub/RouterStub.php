<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Stub;

use R1n0x\BreadcrumbsBundle\Attribute\Route;
use R1n0x\BreadcrumbsBundle\Internal\Resolver\ParametersResolver;
use RuntimeException;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RouterInterface;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
final class RouterStub implements RouterInterface
{
    private ParametersResolver $resolver;

    /** @var array<string, Route> */
    private array $routes = [];

    public function __construct()
    {
        $this->resolver = new ParametersResolver();
    }

    public static function create(): RouterStub
    {
        return new RouterStub();
    }

    public function setContext(RequestContext $context): void {}

    public function getContext(): RequestContext
    {
        return new RequestContext();
    }

    public function getRouteCollection(): RouteCollection
    {
        $collection = new RouteCollection();
        foreach ($this->routes as $route) {
            $collection->add($route->getName(), new \Symfony\Component\Routing\Route(
                $route->getPath(),
                $route->getDefaults()
            ));
        }

        return $collection;
    }

    public function addRouteStub(string $name, string $path, array $defaults = []): static
    {
        $this->routes[$name] = new Route(
            path: $path,
            name: $name,
            defaults: $defaults
        );

        return $this;
    }

    public function generate(string $name, array $parameters = [], int $referenceType = self::ABSOLUTE_PATH): string
    {
        $route = $this->routes[$name] ?? throw new RuntimeException(sprintf('ROUTE "%s" WAS NOT ADDED TO ROUTER STUB', $name));

        $path = $route->getPath();
        foreach ($this->resolver->getParameters($route) as $parameterDefinition) {
            $parameterName = $parameterDefinition->getName();
            $path = str_replace(sprintf('{%s}', $parameterName), $parameters[$parameterName] ?? '', $path);
        }

        return $path;
    }

    public function match(string $pathinfo): array
    {
        return [];
    }
}
