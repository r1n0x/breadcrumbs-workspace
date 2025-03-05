<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\EventListener;

use R1n0x\BreadcrumbsBundle\Context;
use R1n0x\BreadcrumbsBundle\Internal\Holder\ParametersHolder;
use R1n0x\BreadcrumbsBundle\Internal\Model\RouteBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Resolver\NodesResolver;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;

/**
 * @codeCoverageIgnore
 *
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class ControllerArgumentsListener
{
    public function __construct(
        private readonly Context $context,
        private readonly NodesResolver $resolver
    ) {}

    public function __invoke(ControllerArgumentsEvent $event): void
    {
        $request = $event->getRequest();
        $routeName = $request->attributes->getString('_route');
        $node = $this->resolver->get($routeName);
        if (null === $node) {
            return;
        }

        /** @var array<string, string> $parameterValues */
        $parameterValues = $request->attributes->get('_route_params');
        $values = $event->getNamedArguments();

        /** @var RouteBreadcrumbDefinition $definition */
        $definition = $node->getDefinition();
        foreach ($definition->getParameters() as $parameterName) {
            $value = array_key_exists($parameterName, $parameterValues) ? $parameterValues[$parameterName] : ParametersHolder::OPTIONAL_PARAMETER;
            if (ParametersHolder::OPTIONAL_PARAMETER === $value) {
                continue;
            }
            $this->context->setParameter($parameterName, $value, $routeName);
            if ($definition->getPassParametersToExpression()) {
                $this->context->setVariable($parameterName, $values[$parameterName] ?? $parameterValues[$parameterName], $routeName);
            }
        }
    }
}
