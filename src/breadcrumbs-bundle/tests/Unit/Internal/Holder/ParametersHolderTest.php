<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Unit\Internal\Holder;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use R1n0x\BreadcrumbsBundle\Exception\ParameterAlreadyDefinedException;
use R1n0x\BreadcrumbsBundle\Internal\Holder\ParametersHolder;
use R1n0x\BreadcrumbsBundle\Internal\Model\Parameter;
use R1n0x\BreadcrumbsBundle\Tests\Provider\ParametersHolderProvider;
use R1n0x\BreadcrumbsBundle\Tests\Unused;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 *
 * @internal
 */
#[CoversClass(ParametersHolder::class)]
#[UsesClass(ParameterAlreadyDefinedException::class)]
class ParametersHolderTest extends TestCase
{
    private const string MESSAGE_UNEXPECTED_PARAMETER_PATH_VALUE = 'Unexpected parameter path value';
    private const string MESSAGE_UNEXPECTED_PARAMETER_AUTOWIRED_VALUE = 'Unexpected parameter autowired value';
    private const string MESSAGE_UNEXPECTED_PARAMETER_NAME = 'Unexpected parameter name';
    private const string MESSAGE_UNEXPECTED_PARAMETER_ROUTE_NAME = 'Unexpected parameter route name';

    #[Test]
    #[TestDox('Throws exception, when trying to override global parameter')]
    public function throwsExceptionWhenTryingToOverrideGlobalParameter(): void
    {
        $this->expectException(ParameterAlreadyDefinedException::class);

        $this
            ->getService([
                new Parameter(
                    'parameter-8ab56d69-cc2d-4b1a-a7d3-548a3c254d27',
                    null,
                    Unused::null(),
                    Unused::null()
                ),
                new Parameter(
                    'parameter-8ab56d69-cc2d-4b1a-a7d3-548a3c254d27',
                    null,
                    Unused::null(),
                    Unused::null()
                ),
            ]);
    }

    #[Test]
    #[TestDox('Throws exception, when trying to override scoped parameter')]
    public function throwsExceptionWhenTryingToOverrideScopedParameter(): void
    {
        $this->expectException(ParameterAlreadyDefinedException::class);

        $this
            ->getService([
                new Parameter(
                    'parameter-246f238f-8362-4fa9-a8ff-f198be268d6f',
                    'route-138795e4-b900-4fdc-a821-152192d0110a',
                    Unused::null(),
                    Unused::null()
                ),
                new Parameter(
                    'parameter-246f238f-8362-4fa9-a8ff-f198be268d6f',
                    'route-138795e4-b900-4fdc-a821-152192d0110a',
                    Unused::null(),
                    Unused::null()
                ),
            ]);
    }

    #[Test]
    public function returnsGlobalVariable(): void
    {
        $parameter = $this
            ->getService([
                new Parameter(
                    'parameter-01d20805-a39c-4afa-9742-f13916d2faf9',
                    null,
                    'path-value-c9590ea5-69e2-4fe4-89a9-fe9f3675192e',
                    'autowired-value-c70fa6ad-10db-4688-a2e9-8e3cd5a38dc3'
                ),
            ])
            ->get('parameter-01d20805-a39c-4afa-9742-f13916d2faf9');

        $this->assertEquals('path-value-c9590ea5-69e2-4fe4-89a9-fe9f3675192e', $parameter->getPathValue(), self::MESSAGE_UNEXPECTED_PARAMETER_PATH_VALUE);
        $this->assertEquals('autowired-value-c70fa6ad-10db-4688-a2e9-8e3cd5a38dc3', $parameter->getAutowiredValue(), self::MESSAGE_UNEXPECTED_PARAMETER_AUTOWIRED_VALUE);
        $this->assertEquals('parameter-01d20805-a39c-4afa-9742-f13916d2faf9', $parameter->getName(), self::MESSAGE_UNEXPECTED_PARAMETER_NAME);
        $this->assertEquals(null, $parameter->getRouteName(), self::MESSAGE_UNEXPECTED_PARAMETER_ROUTE_NAME);
    }

    #[Test]
    public function returnsScopedParameter(): void
    {
        $parameter = $this
            ->getService([
                new Parameter(
                    'parameter-4c975a1f-3528-435d-a5be-a66f86fd1f41',
                    'route-1dd1bcc1-fc1f-478b-94b6-89f2afe44ef3',
                    'path-value-e008ea90-1e88-486c-8041-3fda52471a5d',
                    'autowired-value-524a37ed-ac9d-4568-a656-295b20ddce08'
                ),
            ])
            ->get('parameter-4c975a1f-3528-435d-a5be-a66f86fd1f41', 'route-1dd1bcc1-fc1f-478b-94b6-89f2afe44ef3');

        $this->assertEquals('path-value-e008ea90-1e88-486c-8041-3fda52471a5d', $parameter->getPathValue(), self::MESSAGE_UNEXPECTED_PARAMETER_PATH_VALUE);
        $this->assertEquals('autowired-value-524a37ed-ac9d-4568-a656-295b20ddce08', $parameter->getAutowiredValue(), self::MESSAGE_UNEXPECTED_PARAMETER_AUTOWIRED_VALUE);
        $this->assertEquals('parameter-4c975a1f-3528-435d-a5be-a66f86fd1f41', $parameter->getName(), self::MESSAGE_UNEXPECTED_PARAMETER_NAME);
        $this->assertEquals('route-1dd1bcc1-fc1f-478b-94b6-89f2afe44ef3', $parameter->getRouteName(), self::MESSAGE_UNEXPECTED_PARAMETER_ROUTE_NAME);
    }

    #[Test]
    public function prioritizesScopedParametersOverGlobalParameters(): void
    {
        $parameter = $this
            ->getService([
                new Parameter(
                    'parameter-5513127f-dc2b-48f4-a15d-12a59a94917c',
                    'route-cdf35504-d123-445f-98fb-25282b38cc8c',
                    'path-value-1710f10b-1970-4433-b36c-1d61279b9aeb',
                    'autowired-value-f60d9f5f-9b02-4d82-86b9-bae92b2d08df'
                ),
                new Parameter(
                    'parameter-5513127f-dc2b-48f4-a15d-12a59a94917c',
                    null,
                    'path-value-b5ec3e50-b4c8-4354-80dd-469fab0cdd98',
                    'autowired-value-ccb8137a-f7aa-4924-b0b5-b181fb7a169d'
                ),
            ])
            ->get('parameter-5513127f-dc2b-48f4-a15d-12a59a94917c', 'route-cdf35504-d123-445f-98fb-25282b38cc8c');

        $this->assertEquals('path-value-1710f10b-1970-4433-b36c-1d61279b9aeb', $parameter->getPathValue(), self::MESSAGE_UNEXPECTED_PARAMETER_PATH_VALUE);
        $this->assertEquals('autowired-value-f60d9f5f-9b02-4d82-86b9-bae92b2d08df', $parameter->getAutowiredValue(), self::MESSAGE_UNEXPECTED_PARAMETER_AUTOWIRED_VALUE);
        $this->assertEquals('parameter-5513127f-dc2b-48f4-a15d-12a59a94917c', $parameter->getName(), self::MESSAGE_UNEXPECTED_PARAMETER_NAME);
        $this->assertEquals('route-cdf35504-d123-445f-98fb-25282b38cc8c', $parameter->getRouteName(), self::MESSAGE_UNEXPECTED_PARAMETER_ROUTE_NAME);
    }

    #[Test]
    public function allowsSettingScopedParameterWithTheSameNameAsGlobalParameter(): void
    {
        $this->expectNotToPerformAssertions();
        $this->getService([
            new Parameter(
                'parameter-dc7b3e63-f541-43e1-9e57-ab6818b3875b',
                'route-ed9c19cd-5034-4a18-8622-bdba473d07b9',
                'path-value-13b6016f-b116-45a8-8c26-2fcb701db0fc',
                'autowired-value-5c45ab2e-a17d-40c1-8e69-b5d50eb88583'
            ),
            new Parameter(
                'parameter-dc7b3e63-f541-43e1-9e57-ab6818b3875b',
                null,
                'path-value-ccf5dda5-fef7-4b0e-accc-1b167f70890f',
                'autowired-value-6bca569a-340b-4170-81ce-6104eea78d53'
            ),
        ]);
    }

    #[Test]
    #[TestDox('Fallbacks to global parameter, if scoped parameter is undefined')]
    public function fallbacksToGlobalParameterIfScopedParameterIsUndefined(): void
    {
        $parameter = $this
            ->getService([
                new Parameter(
                    'parameter-5513127f-dc2b-48f4-a15d-12a59a94917c',
                    null,
                    'path-value-b5ec3e50-b4c8-4354-80dd-469fab0cdd98',
                    'autowired-value-ccb8137a-f7aa-4924-b0b5-b181fb7a169d'
                ),
            ])
            ->get('parameter-5513127f-dc2b-48f4-a15d-12a59a94917c', 'route-cdf35504-d123-445f-98fb-25282b38cc8c');

        $this->assertEquals('path-value-b5ec3e50-b4c8-4354-80dd-469fab0cdd98', $parameter->getPathValue(), self::MESSAGE_UNEXPECTED_PARAMETER_PATH_VALUE);
        $this->assertEquals('autowired-value-ccb8137a-f7aa-4924-b0b5-b181fb7a169d', $parameter->getAutowiredValue(), self::MESSAGE_UNEXPECTED_PARAMETER_AUTOWIRED_VALUE);
        $this->assertEquals('parameter-5513127f-dc2b-48f4-a15d-12a59a94917c', $parameter->getName(), self::MESSAGE_UNEXPECTED_PARAMETER_NAME);
        $this->assertEquals(null, $parameter->getRouteName(), self::MESSAGE_UNEXPECTED_PARAMETER_ROUTE_NAME);
    }

    /**
     * @param array<int, Parameter> $parameters
     */
    private function getService(array $parameters): ParametersHolder
    {
        return ParametersHolderProvider::createWithParameters($parameters);
    }
}
