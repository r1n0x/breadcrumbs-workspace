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
            ->arrayNode('roots')->defaultValue([])
                ->arrayPrototype()
                    ->children()
                        ->scalarNode(R1n0x\BreadcrumbsBundle\Attribute\Route::EXPRESSION)->isRequired()->end()
                        ->scalarNode('route')->defaultNull()->end()
                    ->end()
                ->end()
            ->end()
        ->end();
};