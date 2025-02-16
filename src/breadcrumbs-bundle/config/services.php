<?php

use R1n0x\BreadcrumbsBundle\BreadcrumbsManager;
use R1n0x\BreadcrumbsBundle\Factory\CachePathFactory;
use R1n0x\BreadcrumbsBundle\Factory\ViolationMessageFactory;
use R1n0x\BreadcrumbsBundle\Generator\LabelGenerator;
use R1n0x\BreadcrumbsBundle\Generator\UrlGenerator;
use R1n0x\BreadcrumbsBundle\Loader\ListenableAttributeRouteControllerLoader;
use R1n0x\BreadcrumbsBundle\Resolver\BreadcrumbNodesResolver;
use R1n0x\BreadcrumbsBundle\Resolver\BreadcrumbsResolver;
use R1n0x\BreadcrumbsBundle\Serializer\NodeSerializer;
use R1n0x\BreadcrumbsBundle\Transformer\BreadcrumbDefinitionToNodeTransformer;
use R1n0x\BreadcrumbsBundle\Validator\Node\NodeValidator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\param;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return function (ContainerConfigurator $configurator) {
    $parameters = $configurator->parameters();

    $parameters
        ->set('r1n0x.breadcrumbs.cache.file_name', 'breadcrumbs.json');

    $services = $configurator->services();

    $services->set('r1n0x.breadcrumbs.listener.controller_arguments', R1n0x\BreadcrumbsBundle\EventListener\ControllerArgumentsListener::class)
        ->args([
            service('r1n0x.breadcrumbs.manager'),
            service('r1n0x.breadcrumbs.nodes_provider')
        ])
        ->tag('kernel.event_listener');

    $services
        ->set('r1n0x.breadcrumbs.command.debug_breadcrumbs', R1n0x\BreadcrumbsBundle\Command\DebugBreadcrumbsCommand::class)
        ->args([
            service('r1n0x.breadcrumbs.nodes_provider')
        ])
        ->tag('console.command');

    $services
        ->set('r1n0x.breadcrumbs.loader.listenable_attribute', ListenableAttributeRouteControllerLoader::class)
        ->decorate('routing.loader.attribute', 'r1n0x.breadcrumbs.loader.listenable_attribute');

    $services
        ->set('r1n0x.breadcrumbs.loader.attribute', ListenableAttributeRouteControllerLoader::class)
        ->call('setDispatcher', [
            service('event_dispatcher')
        ])
        ->decorate('routing.loader.attribute');

    $services
        ->set('r1n0x.breadcrumbs.generator.label', LabelGenerator::class)
        ->args([
            service('r1n0x.breadcrumbs.storage.expression_variables'),
            service('r1n0x.breadcrumbs.expression_language')
        ]);

    $services
        ->set('r1n0x.breadcrumbs.generator.url', UrlGenerator::class)
        ->args([
            service('r1n0x.breadcrumbs.storage.router_parameters'),
            service('router')
        ]);

    $services
        ->set('r1n0x.breadcrumbs.storage.expression_variables', R1n0x\BreadcrumbsBundle\Holder\VariablesHolder::class);

    $services
        ->set('r1n0x.breadcrumbs.storage.router_parameters', R1n0x\BreadcrumbsBundle\Holder\ParametersHolder::class);

    $services
        ->set('r1n0x.breadcrumbs.validator', NodeValidator::class)
        ->args([
            service('r1n0x.breadcrumbs.storage.router_parameters'),
            service('r1n0x.breadcrumbs.storage.expression_variables'),
            service('r1n0x.breadcrumbs.builder.violation_message')
        ]);

    $services
        ->set('r1n0x.breadcrumbs.builder', BreadcrumbsResolver::class)
        ->args([
            service('r1n0x.breadcrumbs.nodes_provider'),
            service('r1n0x.breadcrumbs.generator.url'),
            service('r1n0x.breadcrumbs.generator.label'),
            service('r1n0x.breadcrumbs.validator'),
        ]);

    $services
        ->alias(BreadcrumbsResolver::class, 'r1n0x.breadcrumbs.builder');

    $services->set('r1n0x.breadcrumbs.lexer', Symfony\Component\ExpressionLanguage\Lexer::class);

    $services->set('r1n0x.breadcrumbs.expression_language', Symfony\Component\ExpressionLanguage\ExpressionLanguage::class);

    $services->set('r1n0x.breadcrumbs.resolver.expression_variables', R1n0x\BreadcrumbsBundle\Provider\VariablesProvider::class)
        ->args([
            service('r1n0x.breadcrumbs.lexer')
        ]);

    $services->set('r1n0x.breadcrumbs.resolver.route_parameters', R1n0x\BreadcrumbsBundle\Provider\ParametersProvider::class)
        ->args([
            service('router')
        ]);

    $services->set('r1n0x.breadcrumbs.manager', BreadcrumbsManager::class)
        ->args([
            service('r1n0x.breadcrumbs.storage.router_parameters'),
            service('r1n0x.breadcrumbs.storage.expression_variables')
        ]);

    $services->set('r1n0x.breadcrumbs.validator.route', R1n0x\BreadcrumbsBundle\Validator\RouteValidator::class);
    $services->alias(BreadcrumbsManager::class, 'r1n0x.breadcrumbs.manager');

    $services->set('r1n0x.breadcrumbs.serializer', NodeSerializer::class);

    $services->set('r1n0x.breadcrumbs.node_builder', BreadcrumbDefinitionToNodeTransformer::class);

    $services->set('r1n0x.breadcrumbs.builder.violation_message', ViolationMessageFactory::class);

    $services->set('r1n0x.breadcrumbs.cache_warmer', R1n0x\BreadcrumbsBundle\CacheWarmer\BreadcrumbsCacheWarmer::class)
        ->args([
            service('router'),
            service('r1n0x.breadcrumbs.serializer'),
            service("event_dispatcher"),
            service('r1n0x.breadcrumbs.resolver.expression_variables'),
            service('r1n0x.breadcrumbs.resolver.route_parameters'),
            service('r1n0x.breadcrumbs.node_builder'),
            service('r1n0x.breadcrumbs.cache.path_factory'),
            service('r1n0x.breadcrumbs.validator.route'),
            param('r1n0x.breadcrumbs.config.defaults.pass_parameters_to_expression')
        ])
        ->tag('kernel.cache_warmer', ['priority' => -9999999]);

    $services->set('r1n0x.breadcrumbs.nodes_provider', BreadcrumbNodesResolver::class)
        ->args([
            param('kernel.cache_dir'),
            service('r1n0x.breadcrumbs.cache.path_factory'),
            service('r1n0x.breadcrumbs.serializer')
        ]);

    $services->set('r1n0x.breadcrumbs.cache.path_factory', CachePathFactory::class);
};