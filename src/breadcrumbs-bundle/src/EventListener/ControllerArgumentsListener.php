<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\EventListener;

use R1n0x\BreadcrumbsBundle\Exception\ParameterAlreadyDefinedException;
use R1n0x\BreadcrumbsBundle\Internal\Holder\ParametersHolder;
use R1n0x\BreadcrumbsBundle\Internal\Model\Parameter;
use R1n0x\BreadcrumbsBundle\Internal\Model\RouteBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Resolver\NodesResolver;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;

/**
 * @codeCoverageIgnore
 *
 * @author r1n0x <r1n0x-dev@proton.me>
 */
final readonly class ControllerArgumentsListener
{
    public function __construct(
        private ParametersHolder $holder,
        private NodesResolver $resolver
    ) {}

    /**
     * @throws ParameterAlreadyDefinedException
     */
    public function __invoke(ControllerArgumentsEvent $event): void
    {
        $request = $event->getRequest();
        $routeName = $request->attributes->getString('_route');
        $node = $this->resolver->get($routeName);
        if (null === $node) {
            return;
        }

        /** @var array<string, string> $pathValues */
        $pathValues = $request->attributes->get('_route_params');
        $autowiredValues = $event->getNamedArguments();

        /** @var RouteBreadcrumbDefinition $definition */
        $definition = $node->getDefinition();
        foreach ($definition->getParameters() as $parameterDefinition) {
            $parameterName = $parameterDefinition->getName();
            $pathValue = $pathValues[$parameterName] ?? $parameterDefinition->getOptionalValue();
            $this->holder->set(new Parameter(
                $parameterName,
                $routeName,
                $pathValue,
                $autowiredValues[$parameterName] ?? $pathValues[$parameterName]
            ));
        }
    }
}
