<?php

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;

return static function (DefinitionConfigurator $definition): void {
    $definition->rootNode()
        ->children()
            ->arrayNode('defaults')->addDefaultsIfNotSet()
                ->children()
                    ->booleanNode(R1n0x\BreadcrumbsBundle\Attribute\Route::PASS_PARAMETERS_TO_EXPRESSION)->defaultFalse()->end()
                ->end()
            ->end()
            ->arrayNode('root')
                ->children()
                    ->scalarNode(R1n0x\BreadcrumbsBundle\Attribute\Route::EXPRESSION)->defaultNull()->end()
                    ->scalarNode('route')->defaultNull()->end()
                ->end()
            ->end()
        ->end();
};