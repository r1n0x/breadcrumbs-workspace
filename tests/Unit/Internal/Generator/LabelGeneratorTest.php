<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Unit\Internal\Generator;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use R1n0x\BreadcrumbsBundle\Exception\LabelGenerationException;
use R1n0x\BreadcrumbsBundle\Exception\UndefinedVariableException;
use R1n0x\BreadcrumbsBundle\Internal\Generator\LabelGenerator;
use R1n0x\BreadcrumbsBundle\Internal\Holder\VariablesHolder;
use R1n0x\BreadcrumbsBundle\Internal\Model\BreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\RootBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\Variable;
use R1n0x\BreadcrumbsBundle\Internal\Provider\ContextParameterProvider;
use R1n0x\BreadcrumbsBundle\Internal\Provider\ContextVariableProvider;
use R1n0x\BreadcrumbsBundle\Internal\Provider\LabelVariablesProvider;
use R1n0x\BreadcrumbsBundle\Tests\DataProvider\Internal\Generator\LabelGeneratorDataProvider;
use R1n0x\BreadcrumbsBundle\Tests\Doubles\Dummy;
use R1n0x\BreadcrumbsBundle\Tests\Doubles\Fake\LabelGeneratorFake;
use R1n0x\BreadcrumbsBundle\Tests\Doubles\Fake\LabelVariablesProviderFake;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 *
 * @internal
 */
#[CoversClass(LabelGenerator::class)]
#[UsesClass(VariablesHolder::class)]
#[UsesClass(LabelGenerationException::class)]
#[UsesClass(ContextParameterProvider::class)]
#[UsesClass(ContextVariableProvider::class)]
#[UsesClass(LabelVariablesProvider::class)]
#[UsesClass(UndefinedVariableException::class)]
class LabelGeneratorTest extends TestCase
{
    #[Test]
    #[TestDox('Throws exception, when invalid variable value is provided')]
    public function throwsExceptionWhenInvalidVariableValueIsProvided(): void
    {
        $this->expectException(LabelGenerationException::class);

        $this
            ->getService(LabelVariablesProviderFake::createWithVariables([
                new Variable('object', 'obviously_not_an_object'),
            ]))
            ->generate(new RootBreadcrumbDefinition(
                Dummy::null(),
                'object.property',
                Dummy::string(),
                [
                    'object',
                ]
            ));
    }

    #[Test]
    #[TestDox('Throws exception, when no variable value is provided')]
    public function throwsExceptionWhenNoVariableValueIsProvided(): void
    {
        $this->expectException(UndefinedVariableException::class);

        $this
            ->getService(LabelVariablesProviderFake::createWithVariables())
            ->generate(new RootBreadcrumbDefinition(
                Dummy::null(),
                'variable',
                Dummy::string(),
                [
                    'variable',
                ]
            ));
    }

    #[Test]
    #[DataProviderExternal(LabelGeneratorDataProvider::class, 'getGeneratesLabelTestScenarios')]
    public function generatesLabel(
        LabelVariablesProvider $provider,
        BreadcrumbDefinition $definition,
        string $expectedResult
    ): void {
        $this->assertEquals($expectedResult, $this->getService($provider)->generate($definition));
    }

    private function getService(LabelVariablesProvider $provider): LabelGenerator
    {
        return LabelGeneratorFake::create($provider);
    }
}
