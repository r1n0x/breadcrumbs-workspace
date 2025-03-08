<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\param;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

return function (ContainerConfigurator $configurator) {
    $services = $configurator->services();

    $services
        ->set('r1n0x.breadcrumbs.loader.attribute', R1n0x\BreadcrumbsBundle\Loader\ListenableAttributeRouteControllerLoader::class)
        ->call('setDispatcher', [
            service('event_dispatcher')
        ])
        ->decorate('routing.loader.attribute');
};