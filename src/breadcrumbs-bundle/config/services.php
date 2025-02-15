<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ContainerConfigurator $containerConfigurator) {
    $services = $containerConfigurator->services();

    $services->defaults()
        ->autowire()
        ->autoconfigure()
        ->public();

    $services->load('R1n0x\\BreadcrumbsBundle\\', __DIR__ . '/../src/')
        ->exclude([
            __DIR__ . '/../src/Attribute/',
            __DIR__ . '/../src/Dao/'
        ]);
};