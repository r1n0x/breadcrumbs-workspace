<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\param;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

return function (ContainerConfigurator $configurator) {
    $services = $configurator->services();

    $services
        ->set('r1n0x.breadcrumbs.listener.controller_arguments', R1n0x\BreadcrumbsBundle\EventListener\ControllerArgumentsListener::class)
        ->args([
            service('r1n0x.breadcrumbs.manager'),
            service('r1n0x.breadcrumbs.resolver.nodes')
        ])
        ->tag('kernel.event_listener');

    $services
        ->set('r1n0x.breadcrumbs.command.debug_breadcrumbs', R1n0x\BreadcrumbsBundle\Command\DebugBreadcrumbsCommand::class)
        ->args([
            service('r1n0x.breadcrumbs.resolver.nodes')
        ])
        ->tag('console.command');

    $services
        ->set('r1n0x.breadcrumbs.loader.listenable_attribute', R1n0x\BreadcrumbsBundle\Loader\ListenableAttributeRouteControllerLoader::class)
        ->decorate('routing.loader.attribute', 'r1n0x.breadcrumbs.loader.listenable_attribute');

    $services
        ->set('r1n0x.breadcrumbs.loader.attribute', R1n0x\BreadcrumbsBundle\Loader\ListenableAttributeRouteControllerLoader::class)
        ->call('setDispatcher', [
            service('event_dispatcher')
        ])
        ->decorate('routing.loader.attribute');

    $services
        ->set('r1n0x.breadcrumbs.generator.label', R1n0x\BreadcrumbsBundle\Internal\Generator\LabelGenerator::class)
        ->args([
            service('r1n0x.breadcrumbs.holder.variables'),
            service('r1n0x.breadcrumbs.expression_language.engine')
        ]);

    $services
        ->set('r1n0x.breadcrumbs.generator.url', R1n0x\BreadcrumbsBundle\Internal\Generator\UrlGenerator::class)
        ->args([
            service('r1n0x.breadcrumbs.holder.parameters'),
            service('router')
        ]);

    $services
        ->set('r1n0x.breadcrumbs.holder.variables', R1n0x\BreadcrumbsBundle\Internal\Holder\VariablesHolder::class);

    $services
        ->set('r1n0x.breadcrumbs.holder.parameters', R1n0x\BreadcrumbsBundle\Internal\Holder\ParametersHolder::class);

    $services
        ->set('r1n0x.breadcrumbs.validator.node', R1n0x\BreadcrumbsBundle\Internal\Validator\Node\NodeValidator::class)
        ->args([
            service('r1n0x.breadcrumbs.holder.parameters'),
            service('r1n0x.breadcrumbs.holder.variables')
        ]);

    $services
        ->set('r1n0x.breadcrumbs.builder', R1n0x\BreadcrumbsBundle\Internal\BreadcrumbsBuilder::class)
        ->args([
            service('r1n0x.breadcrumbs.resolver.nodes'),
            service('r1n0x.breadcrumbs.generator.url'),
            service('r1n0x.breadcrumbs.generator.label'),
            service('r1n0x.breadcrumbs.validator.node'),
        ]);

    $services
        ->alias(R1n0x\BreadcrumbsBundle\Internal\BreadcrumbsBuilder::class, 'r1n0x.breadcrumbs.builder');


    $services
        ->set('r1n0x.breadcrumbs.expression_language.functions_provider', R1n0x\BreadcrumbsBundle\Internal\FunctionsProvider::class)
        ->args([
            tagged_iterator('r1n0x.breadcrumbs.expression_language.function_provider')
        ]);

    $services
        ->set('r1n0x.breadcrumbs.expression_language.functions', 'array')
        ->factory([
            service('r1n0x.breadcrumbs.expression_language.functions_provider'),
            'getFunctions'
        ]);

    $services
        ->set('r1n0x.breadcrumbs.expression_language.providers', 'array')
        ->factory([
            service('r1n0x.breadcrumbs.expression_language.functions_provider'),
            'getProviders'
        ]);

    $services
        ->set('r1n0x.breadcrumbs.expression_language.lexer', Symfony\Component\ExpressionLanguage\Lexer::class);

    $services
        ->set('r1n0x.breadcrumbs.expression_language.parser', Symfony\Component\ExpressionLanguage\Parser::class)
        ->args([
            service('r1n0x.breadcrumbs.expression_language.functions')
        ]);

    $services
        ->set('r1n0x.breadcrumbs.expression_language.engine', Symfony\Component\ExpressionLanguage\ExpressionLanguage::class)
        ->args([
            null,
            service('r1n0x.breadcrumbs.expression_language.providers')
        ]);

    $services
        ->set('r1n0x.breadcrumbs.resolver.variables', R1n0x\BreadcrumbsBundle\Internal\Resolver\VariablesResolver::class)
        ->args([
            service('r1n0x.breadcrumbs.expression_language.lexer'),
            service('r1n0x.breadcrumbs.expression_language.parser')
        ]);

    $services
        ->set('r1n0x.breadcrumbs.resolver.parameters', R1n0x\BreadcrumbsBundle\Internal\Resolver\ParametersResolver::class)
        ->args([
            service('router')
        ]);

    $services
        ->set('r1n0x.breadcrumbs.manager', R1n0x\BreadcrumbsBundle\BreadcrumbsManager::class)
        ->args([
            service('r1n0x.breadcrumbs.holder.parameters'),
            service('r1n0x.breadcrumbs.holder.variables'),
            service('r1n0x.breadcrumbs.builder')
        ]);

    $services
        ->alias(R1n0x\BreadcrumbsBundle\BreadcrumbsManager::class, 'r1n0x.breadcrumbs.manager');

    $services
        ->set('r1n0x.breadcrumbs.validator.route', R1n0x\BreadcrumbsBundle\Internal\Validator\RouteValidator::class);

    $services
        ->set('r1n0x.breadcrumbs.serializer.node', R1n0x\BreadcrumbsBundle\Internal\NodeSerializer::class);

    $services
        ->set('r1n0x.breadcrumbs.transformer.definition_to_node', R1n0x\BreadcrumbsBundle\Internal\DefinitionToNodeTransformer::class);

    $services
        ->set('r1n0x.breadcrumbs.cache_warmer', R1n0x\BreadcrumbsBundle\CacheWarmer\BreadcrumbsCacheWarmer::class)
        ->args([
            service('r1n0x.breadcrumbs.serializer.node'),
            service('r1n0x.breadcrumbs.transformer.definition_to_node'),
            service('r1n0x.breadcrumbs.cache_reader'),
            service('r1n0x.breadcrumbs.resolver.definitions')
        ])
        ->tag('kernel.cache_warmer');

    $services
        ->set('r1n0x.breadcrumbs.resolver.nodes', R1n0x\BreadcrumbsBundle\Internal\Resolver\NodesResolver::class)
        ->args([
            service('r1n0x.breadcrumbs.cache_reader'),
            service('r1n0x.breadcrumbs.serializer.node'),
            param('kernel.cache_dir')
        ]);

    $services
        ->set('r1n0x.breadcrumbs.cache_reader', R1n0x\BreadcrumbsBundle\Internal\CacheReader::class);

    $services
        ->set('r1n0x.breadcrumbs.resolver.roots', R1n0x\BreadcrumbsBundle\Internal\Resolver\RootsResolver::class)
        ->args([
            param('r1n0x.breadcrumbs.config.roots')
        ]);

    $services->set('r1n0x.breadcrumbs.resolver.definitions', R1n0x\BreadcrumbsBundle\Internal\Resolver\DefinitionsResolver::class)
        ->args([
            service('r1n0x.breadcrumbs.resolver.route_definitions'),
            service('r1n0x.breadcrumbs.resolver.root_definitions')
        ]);

    $services->set('r1n0x.breadcrumbs.resolver.route_definitions', R1n0x\BreadcrumbsBundle\Internal\Resolver\RouteDefinitionsResolver::class)
        ->args([
            service('router'),
            service('event_dispatcher'),
            service('r1n0x.breadcrumbs.resolver.variables'),
            service('r1n0x.breadcrumbs.resolver.parameters'),
            service('r1n0x.breadcrumbs.validator.route'),
            param('r1n0x.breadcrumbs.config.defaults.pass_parameters_to_expression')
        ]);

    $services->set('r1n0x.breadcrumbs.resolver.root_definitions', R1n0x\BreadcrumbsBundle\Internal\Resolver\RootDefinitionsResolver::class)
        ->args([
            service('router'),
            service('r1n0x.breadcrumbs.resolver.variables'),
            service('r1n0x.breadcrumbs.resolver.parameters'),
            service('r1n0x.breadcrumbs.resolver.roots')
        ]);
};