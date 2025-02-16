<?php

namespace R1n0x\BreadcrumbsBundle\EventListener;

use R1n0x\BreadcrumbsBundle\BreadcrumbsManager;
use R1n0x\BreadcrumbsBundle\Holder\ParametersHolder;
use R1n0x\BreadcrumbsBundle\Resolver\BreadcrumbNodesResolver;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class ControllerArgumentsListener
{
    public function __construct(
        private readonly BreadcrumbsManager      $manager,
        private readonly BreadcrumbNodesResolver $resolver
    )
    {
    }


    public function __invoke(ControllerArgumentsEvent $event): void
    {
        $request = $event->getRequest();
        $routeName = $request->attributes->get('_route');
        if (!$routeName) {
            return;
        }
        $node = $this->resolver->getNode($routeName);
        if (!$node) {
            return;
        }
        $values = $request->attributes->get('_route_params');
        $definition = $node->getDefinition();
        foreach ($definition->getParameters() as $parameterName) {
            $value = array_key_exists($parameterName, $values) ? $values[$parameterName] : ParametersHolder::OPTIONAL_PARAMETER;
            if ($value === ParametersHolder::OPTIONAL_PARAMETER) {
                continue;
            }
            $this->manager->setParameter($parameterName, $value, $routeName);
            if ($definition->getPassParametersToExpression()) {
                $this->manager->setVariable($parameterName, $value, $routeName);
            }
        }
    }
}