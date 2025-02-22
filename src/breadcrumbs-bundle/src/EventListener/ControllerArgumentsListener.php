<?php

namespace R1n0x\BreadcrumbsBundle\EventListener;

use R1n0x\BreadcrumbsBundle\BreadcrumbsManager;
use R1n0x\BreadcrumbsBundle\Internal\Holder\ParametersHolder;
use R1n0x\BreadcrumbsBundle\Internal\Model\RouteBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Resolver\NodesResolver;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class ControllerArgumentsListener
{
    public function __construct(
        private readonly BreadcrumbsManager $manager,
        private readonly NodesResolver $resolver
    ) {}

    public function __invoke(ControllerArgumentsEvent $event): void
    {
        $request = $event->getRequest();
        $routeName = $request->attributes->get('_route');
        if (!$routeName) {
            return;
        }
        $node = $this->resolver->get($routeName);
        if (!$node) {
            return;
        }
        $parameterNames = $request->attributes->get('_route_params');
        $values = $event->getNamedArguments();

        /** @var RouteBreadcrumbDefinition $definition */
        $definition = $node->getDefinition();
        foreach ($definition->getParameters() as $parameterName) {
            $value = array_key_exists($parameterName, $parameterNames) ? $parameterNames[$parameterName] : ParametersHolder::OPTIONAL_PARAMETER;
            if (ParametersHolder::OPTIONAL_PARAMETER === $value) {
                continue;
            }
            $this->manager->setParameter($parameterName, $value, $routeName);
            if ($definition->getPassParametersToExpression()) {
                $this->manager->setVariable($parameterName, $values[$parameterName] ?? $parameterNames[$parameterName], $routeName);
            }
        }
    }
}
