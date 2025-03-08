<?php

/**
 * @noinspection PhpUnhandledExceptionInspection
 * @noinspection PhpDocMissingThrowsInspection
 */

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Unit\Internal\Validator\Node;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use R1n0x\BreadcrumbsBundle\Context;
use R1n0x\BreadcrumbsBundle\Exception\UndefinedParameterException;
use R1n0x\BreadcrumbsBundle\Exception\UndefinedVariableException;
use R1n0x\BreadcrumbsBundle\Exception\ValidationException;
use R1n0x\BreadcrumbsBundle\Internal\Holder\ParametersHolder;
use R1n0x\BreadcrumbsBundle\Internal\Holder\VariablesHolder;
use R1n0x\BreadcrumbsBundle\Internal\Model\BreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\BreadcrumbNode;
use R1n0x\BreadcrumbsBundle\Internal\Model\Parameter;
use R1n0x\BreadcrumbsBundle\Internal\Model\ParameterDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\RootBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\RouteBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\Variable;
use R1n0x\BreadcrumbsBundle\Internal\Model\Violation\Error;
use R1n0x\BreadcrumbsBundle\Internal\Model\Violation\RootError;
use R1n0x\BreadcrumbsBundle\Internal\Model\Violation\RouteError;
use R1n0x\BreadcrumbsBundle\Internal\Provider\ContextParameterProvider;
use R1n0x\BreadcrumbsBundle\Internal\Provider\ContextVariableProvider;
use R1n0x\BreadcrumbsBundle\Internal\Validator\Node\NodeContextValidator;
use R1n0x\BreadcrumbsBundle\Internal\Validator\Node\ValidationContext;
use R1n0x\BreadcrumbsBundle\Tests\DataProvider\Internal\Validator\Node\NodeContextValidatorDataProvider;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 *
 * @internal
 */
#[CoversClass(NodeContextValidator::class)]
#[UsesClass(ParametersHolder::class)]
#[UsesClass(VariablesHolder::class)]
#[UsesClass(BreadcrumbDefinition::class)]
#[UsesClass(BreadcrumbNode::class)]
#[UsesClass(ParameterDefinition::class)]
#[UsesClass(RouteBreadcrumbDefinition::class)]
#[UsesClass(RootBreadcrumbDefinition::class)]
#[UsesClass(Error::class)]
#[UsesClass(RouteError::class)]
#[UsesClass(RootError::class)]
#[UsesClass(ValidationContext::class)]
#[UsesClass(Context::class)]
#[UsesClass(Parameter::class)]
#[UsesClass(Variable::class)]
#[UsesClass(ValidationException::class)]
#[UsesClass(UndefinedParameterException::class)]
#[UsesClass(UndefinedVariableException::class)]
#[UsesClass(ContextParameterProvider::class)]
#[UsesClass(ContextVariableProvider::class)]
class NodeContextValidatorTest extends TestCase
{
    #[Test]
    #[DataProviderExternal(NodeContextValidatorDataProvider::class, 'getThrowsExceptionTestScenarios')]
    public function throwsException(
        BreadcrumbNode $node,
        ContextVariableProvider $variableProvider,
        ContextParameterProvider $parameterProvider
    ): void {
        $this->expectException(ValidationException::class);
        $this->getService($variableProvider, $parameterProvider)->validate($node);
    }

    #[Test]
    #[DataProviderExternal(NodeContextValidatorDataProvider::class, 'getValidatesContextTestScenarios')]
    public function validatesContext(
        BreadcrumbNode $node,
        ContextVariableProvider $variableProvider,
        ContextParameterProvider $parameterProvider
    ): void {
        $this->expectNotToPerformAssertions();
        $this->getService($variableProvider, $parameterProvider)->validate($node);
    }

    private function getService(
        ContextVariableProvider $variableProvider,
        ContextParameterProvider $parameterProvider
    ): NodeContextValidator {
        return new NodeContextValidator(
            $variableProvider,
            $parameterProvider
        );
    }
}
