<?php

namespace R1n0x\BreadcrumbsBundle\CacheWarmer;


use R1n0x\BreadcrumbsBundle\Attribute\Route;
use R1n0x\BreadcrumbsBundle\Event\RouteInitializedEvent;
use R1n0x\BreadcrumbsBundle\Exception\FileAccessException;
use R1n0x\BreadcrumbsBundle\Factory\CachePathFactory;
use R1n0x\BreadcrumbsBundle\Model\BreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Model\RootBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Model\RouteBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Provider\RootsProvider;
use R1n0x\BreadcrumbsBundle\Resolver\ParametersResolver;
use R1n0x\BreadcrumbsBundle\Resolver\VariablesResolver;
use R1n0x\BreadcrumbsBundle\Serializer\NodeSerializer;
use R1n0x\BreadcrumbsBundle\Transformer\BreadcrumbDefinitionToNodeTransformer;
use R1n0x\BreadcrumbsBundle\Validator\RouteValidator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class BreadcrumbsCacheWarmer implements CacheWarmerInterface
{
    public function __construct(
        private readonly RouterInterface                       $router,
        private readonly NodeSerializer                        $serializer,
        private readonly EventDispatcherInterface              $dispatcher,
        private readonly VariablesResolver                     $variablesResolver,
        private readonly ParametersResolver                    $parametersResolver,
        private readonly BreadcrumbDefinitionToNodeTransformer $transformer,
        private readonly CachePathFactory                      $pathFactory,
        private readonly RouteValidator                        $validator,
        private readonly RootsProvider                         $rootsProvider,
        private readonly bool                                  $passParametersToExpression
    )
    {
    }

    public function isOptional(): bool
    {
        return false;
    }

    public function warmUp(string $cacheDir): array
    {
        $definitions = $this->getDefinitions();

        $nodes = [];
        foreach ($definitions as $definition) {
            if ($definition instanceof RootBreadcrumbDefinition) {
                continue;
            }
            $nodes[] = $this->transformer->transform($definition, $definitions);
        }

        $status = file_put_contents($this->pathFactory->getFileCachePath($cacheDir), $this->serializer->serialize($nodes));
        if ($status === false) {
            throw new FileAccessException('Breadcrumbs couldn\'t be saved to cache');
        }

        return [];
    }

    /**
     * @return array<int, BreadcrumbDefinition>
     */
    public function getDefinitions(): array
    {
        /** @var array<int, BreadcrumbDefinition> $definitions */
        $definitions = [];

        // I've decided to use an inline listener in this case because I don't want to introduce any storage singleton
        // class which would be referenced only in this cache warmer and some listener, that would be silly and this way is way cleaner in my opinion.
        $listener = function (RouteInitializedEvent $event) use (&$definitions) {
            $route = $event->getRoute();
            $expression = $route->getBreadcrumb()[Route::EXPRESSION] ?? null;
            if (!$expression) {
                return;
            }
            $this->validator->validate($route);
            $definitions[] = new RouteBreadcrumbDefinition(
                $event->getRouteName(),
                $expression,
                $route->getBreadcrumb()[Route::PARENT_ROUTE] ?? null,
                $route->getBreadcrumb()[Route::ROOT] ?? null,
                $route->getBreadcrumb()[Route::PASS_PARAMETERS_TO_EXPRESSION] ?? $this->passParametersToExpression,
                $this->variablesResolver->getVariables($expression),
                $this->parametersResolver->getParameters($route->getPath())
            );
        };

        $this->dispatcher->addListener(RouteInitializedEvent::class, $listener);

        // initialize breadcrumbs (symfony internally initializes all routes when this is executed, which causes listener to be called)
        $this->router->getRouteCollection();

        $this->dispatcher->removeListener(RouteInitializedEvent::class, $listener);

        foreach ($this->rootsProvider->getRoots() as $root) {
            $expression = $root->getExpression();
            $routeName = $root->getRouteName();
            if ($routeName) {
                $route = $this->router->getRouteCollection()->get($routeName);
                if (!$route) {
                    throw new InvalidConfigurationException(sprintf(
                        'Route "%s" referenced in breadcrumbs root "%s" doesn\'t exist.',
                        $routeName,
                        $root->getName()
                    ));
                }
                if (count($this->parametersResolver->getParameters($route->getPath())) > 0) {
                    throw new InvalidConfigurationException(sprintf(
                        'Route "%s" referenced in breadcrumbs root "%s" cannot be dynamic.',
                        $routeName,
                        $root->getName()
                    ));
                }
            }
            $definitions[] = new RootBreadcrumbDefinition(
                $routeName,
                $expression,
                $root->getName(),
                $this->variablesResolver->getVariables($expression)
            );
        }

        return $definitions;
    }
}