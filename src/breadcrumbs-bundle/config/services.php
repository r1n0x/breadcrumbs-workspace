<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return function (ContainerConfigurator $configurator) {
    $services = $configurator->services();

    $services
        ->set('r1n0x.breadcrumbs.command.debug_breadcrumbs', R1n0x\BreadcrumbsBundle\Command\DebugBreadcrumbsCommand::class)
        ->args([
            service('router'),
            service('r1n0x.breadcrumbs.storage.breadcrumbs'),
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
        ->set('r1n0x.breadcrumbs.storage.breadcrumbs', R1n0x\BreadcrumbsBundle\Storage\BreadcrumbsStorage::class)
        ->args([
            service('router')
        ]);


    $services
        ->set('r1n0x.breadcrumbs.generator.label', \R1n0x\BreadcrumbsBundle\Generator\LabelGenerator::class)
        ->args([
            service('r1n0x.breadcrumbs.storage.expression_variables'),
            service('r1n0x.breadcrumbs.expression_language')
        ]);

    $services
        ->set('r1n0x.breadcrumbs.generator.url', \R1n0x\BreadcrumbsBundle\Generator\UrlGenerator::class)
        ->args([
            service('r1n0x.breadcrumbs.storage.router_parameters'),
            service('router')
        ]);

    $services
        ->set('r1n0x.breadcrumbs.storage.expression_variables', R1n0x\BreadcrumbsBundle\Storage\ExpressionVariablesStorage::class);

    $services
        ->set('r1n0x.breadcrumbs.storage.router_parameters', R1n0x\BreadcrumbsBundle\Storage\RouterParametersStorage::class);

    $services
        ->set('r1n0x.breadcrumbs.validator', R1n0x\BreadcrumbsBundle\Validator::class)
        ->args([
            service('r1n0x.breadcrumbs.storage.router_parameters'),
            service('r1n0x.breadcrumbs.storage.expression_variables')
        ]);

    $services
        ->set('r1n0x.breadcrumbs.builder', R1n0x\BreadcrumbsBundle\BreadcrumbsBuilder::class)
        ->args([
            service('r1n0x.breadcrumbs.resolver.breadcrumbs'),
            service('r1n0x.breadcrumbs.generator.url'),
            service('r1n0x.breadcrumbs.generator.label'),
            service('r1n0x.breadcrumbs.validator')
        ]);

    $services
        ->alias(R1n0x\BreadcrumbsBundle\BreadcrumbsBuilder::class, 'r1n0x.breadcrumbs.builder');

    $services
        ->set('r1n0x.breadcrumbs.listener.route_build', R1n0x\BreadcrumbsBundle\EventListener\RouteBuildListener::class)
        ->args([
            service('r1n0x.breadcrumbs.storage.breadcrumbs'),
            service('r1n0x.breadcrumbs.resolver.expression_variables'),
            service('r1n0x.breadcrumbs.resolver.route_parameters')
        ])
        ->tag('kernel.event_listener');

    $services->set('r1n0x.breadcrumbs.lexer', Symfony\Component\ExpressionLanguage\Lexer::class);

    $services->set('r1n0x.breadcrumbs.expression_language', Symfony\Component\ExpressionLanguage\ExpressionLanguage::class);

    $services->set('r1n0x.breadcrumbs.resolver.expression_variables', R1n0x\BreadcrumbsBundle\Resolver\ExpressionVariablesResolver::class)
        ->args([
            service('r1n0x.breadcrumbs.lexer')
        ]);

    $services->set('r1n0x.breadcrumbs.resolver.route_parameters', R1n0x\BreadcrumbsBundle\Resolver\RouteParametersResolver::class)
        ->args([
            service('router')
        ]);

    $services->set('r1n0x.breadcrumbs.resolver.breadcrumbs', R1n0x\BreadcrumbsBundle\Resolver\BreadcrumbsResolver::class)
        ->args([
            service('r1n0x.breadcrumbs.storage.breadcrumbs')
        ]);

    $services->set('r1n0x.breadcrumbs.manager', \R1n0x\BreadcrumbsBundle\BreadcrumbsManager::class)
        ->args([
            service('r1n0x.breadcrumbs.storage.router_parameters'),
            service('r1n0x.breadcrumbs.storage.expression_variables')
        ]);

    $services->alias(\R1n0x\BreadcrumbsBundle\BreadcrumbsManager::class, 'r1n0x.breadcrumbs.manager');
};