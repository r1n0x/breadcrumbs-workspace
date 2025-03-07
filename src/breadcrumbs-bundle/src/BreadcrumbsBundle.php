<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle;

use R1n0x\BreadcrumbsBundle\Attribute\Route;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

/**
 * @codeCoverageIgnore
 *
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class BreadcrumbsBundle extends AbstractBundle
{
    /**
     * @phpstan-ignore missingType.iterableValue
     */
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import('../config/services.php');
        $container->parameters()
            ->set('r1n0x.breadcrumbs.config.defaults.pass_parameters_to_expression', $config['defaults'][Route::PASS_PARAMETERS_TO_EXPRESSION])
            ->set('r1n0x.breadcrumbs.config.roots', $config['roots']);
    }

    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->import('../config/definition.php');
    }
}
