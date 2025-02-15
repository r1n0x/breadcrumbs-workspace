<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return function (ContainerConfigurator $containerConfigurator) {
    $services = $containerConfigurator->services();

    $services
        ->set('r1n0x.breadcrumbs.command.debug_breadcrumbs', R1n0x\BreadcrumbsBundle\Command\DebugBreadcrumbsCommand::class)
        ->args([
            service('router'),
            service('r1n0x.breadcrumbs.storage'),
        ])
        ->tag('console.command');

    $services
        ->set('r1n0x.breadcrumbs.loader.attribute', R1n0x\BreadcrumbsBundle\AttributeRouteControllerLoader::class)
        ->decorate('routing.loader.attribute', 'r1n0x.breadcrumbs.loader.attribute');

    $services
        ->set('r1n0x.breadcrumbs.loader.attribute', R1n0x\BreadcrumbsBundle\AttributeRouteControllerLoader::class)
        ->call('setDispatcher', [
            service('event_dispatcher')
        ])
        ->decorate('routing.loader.attribute');

    $services
        ->set('r1n0x.breadcrumbs.storage', R1n0x\BreadcrumbsBundle\BreadcrumbsStorage::class)
        ->args([
            service('router')
        ]);

    $services
        ->set('r1n0x.breadcrumbs.builder', R1n0x\BreadcrumbsBundle\BreadcrumbsBuilder::class)
        ->args([
            service('r1n0x.breadcrumbs.storage')
        ]);

    $services
        ->alias(R1n0x\BreadcrumbsBundle\BreadcrumbsBuilder::class, 'r1n0x.breadcrumbs.builder');

    $services
        ->set('r1n0x.breadcrumbs.listener.route_build', R1n0x\BreadcrumbsBundle\EventListener\RouteBuildListener::class)
        ->args([
            service("r1n0x.breadcrumbs.storage")
        ])
        ->tag('kernel.event_listener');
};