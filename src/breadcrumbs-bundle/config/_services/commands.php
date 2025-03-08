<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\param;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

return function (ContainerConfigurator $configurator) {
    $services = $configurator->services();

    $services
        ->set('r1n0x.breadcrumbs.command.debug_breadcrumbs', R1n0x\BreadcrumbsBundle\Command\DebugBreadcrumbsCommand::class)
        ->args([
            service('r1n0x.breadcrumbs.resolver.nodes')
        ])
        ->tag('console.command');
};