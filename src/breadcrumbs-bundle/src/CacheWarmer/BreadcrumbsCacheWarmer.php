<?php

namespace R1n0x\BreadcrumbsBundle\CacheWarmer;


use R1n0x\BreadcrumbsBundle\Attribute\Route;
use R1n0x\BreadcrumbsBundle\Event\RouteInitializedEvent;
use R1n0x\BreadcrumbsBundle\Exception\FileAccessException;
use R1n0x\BreadcrumbsBundle\Factory\CachePathFactory;
use R1n0x\BreadcrumbsBundle\Model\BreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Provider\ParametersProvider;
use R1n0x\BreadcrumbsBundle\Provider\VariablesProvider;
use R1n0x\BreadcrumbsBundle\Serializer\NodeSerializer;
use R1n0x\BreadcrumbsBundle\Transformer\BreadcrumbDefinitionToNodeTransformer;
use R1n0x\BreadcrumbsBundle\Validator\RouteValidator;
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
        private readonly VariablesProvider                     $variablesProvider,
        private readonly ParametersProvider                    $parametersProvider,
        private readonly BreadcrumbDefinitionToNodeTransformer $transformer,
        private readonly CachePathFactory                      $pathFactory,
        private readonly RouteValidator                        $validator,
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
            $nodes[] = $this->transformer->transform($definition->getRouteName(), $definitions);
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
            $definitions[] = new BreadcrumbDefinition(
                $event->getRouteName(),
                $expression,
                $route->getBreadcrumb()[Route::PARENT_ROUTE] ?? null,
                $route->getBreadcrumb()[Route::PASS_PARAMETERS_TO_EXPRESSION] ?? $this->passParametersToExpression,
                $this->variablesProvider->getVariables($expression),
                $this->parametersProvider->getParameters($route->getPath())
            );
        };

        $this->dispatcher->addListener(RouteInitializedEvent::class, $listener);

        // initialize breadcrumbs (symfony internally initializes all routes when this is executed, which causes listener to be called)
        $this->router->getRouteCollection();

        $this->dispatcher->removeListener(RouteInitializedEvent::class, $listener);

        return $definitions;
    }
}