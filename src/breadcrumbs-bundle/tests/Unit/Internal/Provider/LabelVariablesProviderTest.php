<?php

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
use R1n0x\BreadcrumbsBundle\Tests\Doubles\Dummy;
use R1n0x\BreadcrumbsBundle\Tests\Doubles\Fake\ContextParameterProviderFake;
use R1n0x\BreadcrumbsBundle\Tests\Doubles\Fake\ContextVariableProviderFake;

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
    public function providesVariablesForRoute(): void
    {
        $variables = $this
            ->getService(
                ContextVariableProviderFake::createWithVariables(
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
                    ContextParameterProviderFake::createWithParameters()
                )
            )
            ->getVariables(new RouteBreadcrumbDefinition(
                Dummy::string(),
                Dummy::string(),
                Dummy::string(),
                Dummy::string(),
                Dummy::bool(),
                Dummy::array(),
                [
                    'variable_name_1',
                    'variable_name_2',
                ]
            ));

        $this->assertArrayHasKey('variable_name_1', $variables);
        $this->assertArrayHasKey('variable_name_2', $variables);
        $this->assertEquals('variable_value_1', $variables['variable_name_1']);
        $this->assertEquals('variable_value_2', $variables['variable_name_2']);
    }

    #[Test]
    public function providesVariablesForRoot(): void
    {
        $variables = $this
            ->getService(
                ContextVariableProviderFake::createWithVariables(
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
                    ContextParameterProviderFake::createWithParameters()
                )
            )
            ->getVariables(
                new RootBreadcrumbDefinition(
                    Dummy::string(),
                    Dummy::string(),
                    Dummy::string(),
                    [
                        'variable_name_1',
                        'variable_name_2',
                    ]
                )
            );

        $this->assertArrayHasKey('variable_name_1', $variables);
        $this->assertArrayHasKey('variable_name_2', $variables);
        $this->assertEquals('variable_value_1', $variables['variable_name_1']);
        $this->assertEquals('variable_value_2', $variables['variable_name_2']);
    }

    private function getService(ContextVariableProvider $provider): LabelVariablesProvider
    {
        return new LabelVariablesProvider($provider);
    }
}
