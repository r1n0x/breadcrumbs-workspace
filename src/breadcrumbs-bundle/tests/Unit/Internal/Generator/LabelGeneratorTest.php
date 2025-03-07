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
use R1n0x\BreadcrumbsBundle\Internal\Generator\LabelGenerator;
use R1n0x\BreadcrumbsBundle\Internal\Holder\VariablesHolder;
use R1n0x\BreadcrumbsBundle\Internal\Model\BreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\RootBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\Variable;
use R1n0x\BreadcrumbsBundle\Tests\DataProvider\Internal\Generator\LabelGeneratorDataProvider;
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
class LabelGeneratorTest extends TestCase
{
    #[Test]
    #[TestDox('Throws exception, when invalid variable value is provided')]
    public function throwsExceptionWhenInvalidVariableValueIsProvided(): void
    {
        $this->expectException(LabelGenerationException::class);
        $holder = new VariablesHolder();
        $holder
            ->set(new Variable('expected_object1', 'obviously_not_an_object'));

        $this->getService()->generate(new RootBreadcrumbDefinition(
            Unused::null(),
            'expected_object1.property_name',
            Unused::string(),
            [
                'expected_object1',
            ]
        ), $holder);
    }

    #[Test]
    #[DataProviderExternal(LabelGeneratorDataProvider::class, 'getGeneratesLabelTestScenarios')]
    public function generatesLabel(
        VariablesHolder $holder,
        BreadcrumbDefinition $definition,
        string $expectedResult
    ): void {
        $this->assertEquals($expectedResult, $this->getService()->generate($definition, $holder));
    }

    private function getService(): LabelGenerator
    {
        return new LabelGenerator(new ExpressionLanguage());
    }
}
