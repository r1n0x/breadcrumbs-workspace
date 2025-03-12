<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Doubles\Fake;

use R1n0x\BreadcrumbsBundle\Internal\Provider\ContextParameterProvider;
use R1n0x\BreadcrumbsBundle\Internal\Provider\ContextVariableProvider;
use R1n0x\BreadcrumbsBundle\Internal\Validator\Node\NodeContextValidator;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
final readonly class NodeContextValidatorFake
{
    public static function create(
        ContextVariableProvider $variableProvider,
        ContextParameterProvider $parameterProvider
    ): NodeContextValidator {
        return new NodeContextValidator(
            $variableProvider,
            $parameterProvider
        );
    }
}
