<?php

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;

return static function (DefinitionConfigurator $definition): void {
    $definition->rootNode()
        ->children()
            ->arrayNode('root')
                ->children()
                    ->scalarNode('expression')->defaultNull()->end()
                    ->scalarNode('route')->defaultNull()->end()
                ->end()
            ->end()
        ->end();
};