<?php

/**
 * @noinspection PhpUnhandledExceptionInspection
 * @noinspection PhpDocMissingThrowsInspection
 */

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Unit\Internal\Provider;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use R1n0x\BreadcrumbsBundle\Internal\Holder\VariablesHolder;
use R1n0x\BreadcrumbsBundle\Internal\Model\RootBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\RouteBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\Variable;
use R1n0x\BreadcrumbsBundle\Internal\Provider\ContextParameterProvider;
use R1n0x\BreadcrumbsBundle\Internal\Provider\ContextVariableProvider;
use R1n0x\BreadcrumbsBundle\Internal\Provider\LabelVariablesProvider;
use R1n0x\BreadcrumbsBundle\Tests\Provider\ContextParameterProviderProvider;
use R1n0x\BreadcrumbsBundle\Tests\Provider\ContextVariableProviderProvider;
use R1n0x\BreadcrumbsBundle\Tests\Unused;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 *
 * @internal
 */
#[CoversClass(LabelVariablesProvider::class)]
#[UsesClass(VariablesHolder::class)]
#[UsesClass(ContextParameterProvider::class)]
#[UsesClass(ContextVariableProvider::class)]
class LabelVariablesProviderTest extends TestCase
{
    #[Test]
    public function providesVariablesForRouteBreadcrumb(): void
    {
        $variables = $this
            ->getService(
                ContextVariableProviderProvider::createWithVariables(
                    [
                        new Variable(
                            'variable_name_1',
                            'variable_value_1'
                        ),
                        new Variable(
                            'variable_name_2',
                            'variable_value_2'
                        ),
                    ],
                    ContextParameterProviderProvider::empty()
                )
            )->getVariables(new RouteBreadcrumbDefinition(
                Unused::string(),
                Unused::string(),
                Unused::string(),
                Unused::string(),
                Unused::bool(),
                Unused::array(),
                [
                    'variable_name_1',
                    'variable_name_2',
                ]
            ));

        $this->arrayHasKey('variable_name_1')->evaluate($variables);
        $this->arrayHasKey('variable_name_2')->evaluate($variables);
        $this->assertEquals('variable_value_1', $variables['variable_name_1']);
        $this->assertEquals('variable_value_2', $variables['variable_name_2']);
    }

    #[Test]
    public function providesVariablesForRootBreadcrumb(): void
    {
        $variables = $this
            ->getService(
                ContextVariableProviderProvider::createWithVariables(
                    [
                        new Variable(
                            'variable_name_1',
                            'variable_value_1'
                        ),
                        new Variable(
                            'variable_name_2',
                            'variable_value_2'
                        ),
                    ],
                    ContextParameterProviderProvider::empty()
                )
            )->getVariables(new RootBreadcrumbDefinition(
                Unused::string(),
                Unused::string(),
                Unused::string(),
                [
                    'variable_name_1',
                    'variable_name_2',
                ]
            ));

        $this->arrayHasKey('variable_name_1')->evaluate($variables);
        $this->arrayHasKey('variable_name_2')->evaluate($variables);
        $this->assertEquals('variable_value_1', $variables['variable_name_1']);
        $this->assertEquals('variable_value_2', $variables['variable_name_2']);
    }

    private function getService(ContextVariableProvider $provider): LabelVariablesProvider
    {
        return new LabelVariablesProvider($provider);
    }
}
