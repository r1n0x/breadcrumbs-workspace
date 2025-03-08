<?php

/**
 * @noinspection PhpUnhandledExceptionInspection
 * @noinspection PhpDocMissingThrowsInspection
 */

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
use R1n0x\BreadcrumbsBundle\Tests\Provider\LabelVariablesProviderProvider;
use R1n0x\BreadcrumbsBundle\Tests\Unused;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

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
            ->getService(LabelVariablesProviderProvider::createWithVariables([
                new Variable('expected_object1', 'obviously_not_an_object'),
            ]))
            ->generate(new RootBreadcrumbDefinition(
                Unused::null(),
                'expected_object1.property_name',
                Unused::string(),
                [
                    'expected_object1',
                ]
            ));
    }

    #[Test]
    #[TestDox('Throws exception, when no variable is provided')]
    public function throwsExceptionWhenNoVariableIsProvided(): void
    {
        $this->expectException(UndefinedVariableException::class);
        $this
            ->getService(LabelVariablesProviderProvider::empty())
            ->generate(new RootBreadcrumbDefinition(
                Unused::null(),
                'some_variable',
                Unused::string(),
                [
                    'some_variable',
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
        return new LabelGenerator(
            new ExpressionLanguage(),
            $provider
        );
    }
}
