<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundleTools\Phpstan;

use PHPStan\Reflection\AllowedSubTypesClassReflectionExtension;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\ObjectType;
use R1n0x\BreadcrumbsBundle\Internal\Model\Violation\Error;
use R1n0x\BreadcrumbsBundle\Internal\Model\Violation\RootError;
use R1n0x\BreadcrumbsBundle\Internal\Model\Violation\RouteError;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class SealedErrorClass implements AllowedSubTypesClassReflectionExtension
{
    public function supports(ClassReflection $classReflection): bool
    {
        return Error::class === $classReflection->getName();
    }

    public function getAllowedSubTypes(ClassReflection $classReflection): array
    {
        return [
            new ObjectType(RouteError::class),
            new ObjectType(RootError::class),
        ];
    }
}
