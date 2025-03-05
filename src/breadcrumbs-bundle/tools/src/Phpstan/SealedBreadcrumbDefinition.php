<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundleTools\Phpstan;

use PHPStan\Reflection\AllowedSubTypesClassReflectionExtension;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\ObjectType;
use R1n0x\BreadcrumbsBundle\Internal\Model\BreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\RootBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\RouteBreadcrumbDefinition;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class SealedBreadcrumbDefinition implements AllowedSubTypesClassReflectionExtension
{
    public function supports(ClassReflection $classReflection): bool
    {
        return BreadcrumbDefinition::class === $classReflection->getName();
    }

    public function getAllowedSubTypes(ClassReflection $classReflection): array
    {
        return [
            new ObjectType(RouteBreadcrumbDefinition::class),
            new ObjectType(RootBreadcrumbDefinition::class),
        ];
    }
}
