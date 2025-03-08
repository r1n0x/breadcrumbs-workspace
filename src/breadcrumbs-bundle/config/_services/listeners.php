<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\param;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

return function (ContainerConfigurator $configurator) {
    $services = $configurator->services();

    if($configurator->env() === 'dev') {
        $services
            ->set('r1n0x.breadcrumbs.listener.request', R1n0x\BreadcrumbsBundle\EventListener\DebugRequestListener::class)
            ->args([
                service('r1n0x.breadcrumbs.cache_warmer'),
                param('kernel.cache_dir'),
            ])
            ->tag('kernel.event_listener');
    }

    $services
        ->set('r1n0x.breadcrumbs.listener.controller_arguments', R1n0x\BreadcrumbsBundle\EventListener\ControllerArgumentsListener::class)
        ->args([
            service('r1n0x.breadcrumbs.holder.parameters'),
            service('r1n0x.breadcrumbs.resolver.nodes')
        ])
        ->tag('kernel.event_listener');
};