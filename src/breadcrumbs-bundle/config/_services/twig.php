<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\param;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

return function (ContainerConfigurator $configurator) {
    $services = $configurator->services();

    $services
        ->set('r1n0x.breadcrumbs.twig.extension', R1n0x\BreadcrumbsBundle\Twig\BreadcrumbsExtension::class)
        ->args([
            service('request_stack'),
            service('r1n0x.breadcrumbs.builder'),
            service('r1n0x.breadcrumbs.context')
        ])
        ->tag('twig.extension');
};