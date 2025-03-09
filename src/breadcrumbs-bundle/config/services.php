<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\param;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

return function (ContainerConfigurator $configurator) {
    $configurator->import('./_services/listeners.php');
    $configurator->import('./_services/loaders.php');
    $configurator->import('./_services/commands.php');
    $configurator->import('./_services/twig.php');

    $services = $configurator->services();

    $services
        ->set('r1n0x.breadcrumbs.generator.label', R1n0x\BreadcrumbsBundle\Internal\Generator\LabelGenerator::class)
        ->args([
            service('r1n0x.breadcrumbs.expression_language.engine'),
            service('r1n0x.breadcrumbs.provider.label_variables')
        ]);

    $services
        ->set('r1n0x.breadcrumbs.generator.url', R1n0x\BreadcrumbsBundle\Internal\Generator\UrlGenerator::class)
        ->args([
            service('router'),
            service('r1n0x.breadcrumbs.provider.url_parameters')
        ]);

    $services
        ->set('r1n0x.breadcrumbs.holder.variables', R1n0x\BreadcrumbsBundle\Internal\Holder\VariablesHolder::class);

    $services
        ->set('r1n0x.breadcrumbs.holder.parameters', R1n0x\BreadcrumbsBundle\Internal\Holder\ParametersHolder::class);

    $services
        ->set('r1n0x.breadcrumbs.validator.node', R1n0x\BreadcrumbsBundle\Internal\Validator\Node\NodeContextValidator::class)
        ->args([
            service('r1n0x.breadcrumbs.provider.context_variable'),
            service('r1n0x.breadcrumbs.provider.context_parameter')
        ]);

    $services
        ->set('r1n0x.breadcrumbs.builder', R1n0x\BreadcrumbsBundle\Builder::class)
        ->args([
            service('r1n0x.breadcrumbs.resolver.nodes'),
            service('r1n0x.breadcrumbs.generator.url'),
            service('r1n0x.breadcrumbs.generator.label'),
            service('r1n0x.breadcrumbs.validator.node'),
        ]);

    $services
        ->alias(R1n0x\BreadcrumbsBundle\Builder::class, 'r1n0x.breadcrumbs.builder');


    $services
        ->set('r1n0x.breadcrumbs.provider.expression_language_functions', R1n0x\BreadcrumbsBundle\Internal\Provider\FunctionsProvider::class)
        ->args([
            tagged_iterator('r1n0x.breadcrumbs.expression_language.function_provider')
        ]);

    $services
        ->set('r1n0x.breadcrumbs.expression_language.functions', 'array')
        ->factory([
            service('r1n0x.breadcrumbs.provider.expression_language_functions'),
            'getFunctions'
        ]);

    $services
        ->set('r1n0x.breadcrumbs.expression_language.providers', 'array')
        ->factory([
            service('r1n0x.breadcrumbs.provider.expression_language_functions'),
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
        ->set('r1n0x.breadcrumbs.context', R1n0x\BreadcrumbsBundle\Context::class)
        ->args([
            service('r1n0x.breadcrumbs.holder.parameters'),
            service('r1n0x.breadcrumbs.holder.variables'),
            service('r1n0x.breadcrumbs.builder')
        ]);

    $services
        ->alias(R1n0x\BreadcrumbsBundle\Context::class, 'r1n0x.breadcrumbs.context');

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
            service('r1n0x.breadcrumbs.provider.routes'),
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

    $services
        ->set('r1n0x.breadcrumbs.provider.routes', R1n0x\BreadcrumbsBundle\Internal\Resolver\RoutesProvider::class)
        ->args([
            service('router'),
            service('event_dispatcher'),
        ]);

    $services
        ->set('r1n0x.breadcrumbs.provider.context_parameter', R1n0x\BreadcrumbsBundle\Internal\Provider\ContextParameterProvider::class)
        ->args([
            service('r1n0x.breadcrumbs.holder.parameters')
        ]);

    $services
        ->set('r1n0x.breadcrumbs.provider.url_parameters', R1n0x\BreadcrumbsBundle\Internal\Provider\UrlParametersProvider::class)
        ->args([
            service('r1n0x.breadcrumbs.provider.context_parameter')
        ]);

    $services
        ->set('r1n0x.breadcrumbs.provider.context_variable', R1n0x\BreadcrumbsBundle\Internal\Provider\ContextVariableProvider::class)
        ->args([
            service('r1n0x.breadcrumbs.holder.variables'),
            service('r1n0x.breadcrumbs.provider.context_parameter')
        ]);

    $services
        ->set('r1n0x.breadcrumbs.provider.label_variables', R1n0x\BreadcrumbsBundle\Internal\Provider\LabelVariablesProvider::class)
        ->args([
            service('r1n0x.breadcrumbs.provider.context_variable'),
            service('r1n0x.breadcrumbs.provider.context_parameter')
        ]);
};