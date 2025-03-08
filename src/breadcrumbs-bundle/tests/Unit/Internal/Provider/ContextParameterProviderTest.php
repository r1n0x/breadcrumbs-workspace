<?php

/**
 * @noinspection PhpUnhandledExceptionInspection
 * @noinspection PhpDocMissingThrowsInspection
 */

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Unit\Internal\Provider;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use R1n0x\BreadcrumbsBundle\Exception\UndefinedParameterException;
use R1n0x\BreadcrumbsBundle\Internal\Holder\ParametersHolder;
use R1n0x\BreadcrumbsBundle\Internal\Model\Parameter;
use R1n0x\BreadcrumbsBundle\Internal\Model\ParameterDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\RouteBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Provider\ContextParameterProvider;
use R1n0x\BreadcrumbsBundle\Tests\Provider\ParametersHolderProvider;
use R1n0x\BreadcrumbsBundle\Tests\Unused;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 *
 * @internal
 */
#[CoversClass(ContextParameterProvider::class)]
#[UsesClass(ParametersHolder::class)]
#[UsesClass(UndefinedParameterException::class)]
class ContextParameterProviderTest extends TestCase
{
    #[Test]
    #[TestDox('Throws exception, if getting undefined parameter')]
    public function throwsExceptionIfGettingUndefinedParameter(): void
    {
        $this->expectException(UndefinedParameterException::class);
        $this->getService(ParametersHolderProvider::empty())
            ->get('name-60b6db57-f25d-4d42-b102-82f6f038cfe5', null);
    }

    #[Test]
    #[TestDox('Throws exception, if getting undefined parameter for definition')]
    public function throwsExceptionIfGettingUndefinedParameterForDefinition(): void
    {
        $this->expectException(UndefinedParameterException::class);
        $this->getService(ParametersHolderProvider::empty())
            ->getForDefinition(new RouteBreadcrumbDefinition(
                'route-ed876c1e-3289-4fb3-b071-dbe6886e81c7',
                'expression-b33035a1-9b71-4cbe-a99c-e13de77fe11f',
                null,
                'root-cfdf6489-7a47-4c51-bde2-bc0e9c61158a',
                Unused::bool(),
                [
                    new ParameterDefinition(
                        'name-bf52a821-19e1-4176-b95d-aba6a1bd2489',
                        false,
                        null
                    ),
                ],
            ), 'name-cec5b35e-0228-42a3-9dfb-20b701c3158a', null);
    }

    #[Test]
    public function providesScopedParameter(): void
    {
        $parameter = $this->getService(ParametersHolderProvider::createWithParameters([
            new Parameter(
                'name-15000590-a058-4e9e-a9fa-b36bdde31228',
                null,
                'path-value-2b46bd2f-cffa-4e73-b1d1-bbd32129e1d4',
                'autowired-value-6b9803b1-c578-4bc5-b5c2-6c04819a9796'
            ),
        ]))->get('name-15000590-a058-4e9e-a9fa-b36bdde31228', null);

        $this->assertEquals(null, $parameter->getRouteName());
        $this->assertEquals('path-value-2b46bd2f-cffa-4e73-b1d1-bbd32129e1d4', $parameter->getPathValue());
        $this->assertEquals('autowired-value-6b9803b1-c578-4bc5-b5c2-6c04819a9796', $parameter->getAutowiredValue());
        $this->assertEquals('name-15000590-a058-4e9e-a9fa-b36bdde31228', $parameter->getName());
    }

    #[Test]
    public function providesGlobalParameter(): void
    {
        $parameter = $this->getService(ParametersHolderProvider::createWithParameters([
            new Parameter(
                'name-cb4f8bbf-e23c-4090-8c81-9d5b9442d32a',
                null,
                'path-value-2868f7bd-804c-4742-80de-10344f4b895f',
                'autowired-value-c7cf5430-38ba-4098-9530-59e8666625df'
            ),
        ]))->get('name-cb4f8bbf-e23c-4090-8c81-9d5b9442d32a', null);

        $this->assertEquals(null, $parameter->getRouteName());
        $this->assertEquals('path-value-2868f7bd-804c-4742-80de-10344f4b895f', $parameter->getPathValue());
        $this->assertEquals('autowired-value-c7cf5430-38ba-4098-9530-59e8666625df', $parameter->getAutowiredValue());
        $this->assertEquals('name-cb4f8bbf-e23c-4090-8c81-9d5b9442d32a', $parameter->getName());
    }

    #[Test]
    public function providesParameterForDefinition(): void
    {
        $parameter = $this->getService(ParametersHolderProvider::createWithParameters([
            new Parameter(
                'name-53943916-6bf0-43ac-b8c0-a722dd0df0fe',
                null,
                'path-value-0d9991c1-0038-4a77-935e-3df9f34c245e',
                'autowired-value-f6737e3d-8e01-4cbd-8ddb-6a82a01c9d40'
            ),
        ]))->getForDefinition(new RouteBreadcrumbDefinition(
            'route-5b1e24f3-afad-4c48-898a-a57a693b11b7',
            'expression-8a6ac04c-c169-4e29-a0d6-32c68442e41d',
            null,
            'root-31177a49-8682-4ad7-acc2-0d41f4247ede',
            Unused::bool(),
            [
                new ParameterDefinition(
                    'name-53943916-6bf0-43ac-b8c0-a722dd0df0fe',
                    false,
                    null
                ),
            ],
        ), 'name-53943916-6bf0-43ac-b8c0-a722dd0df0fe', 'route-5b1e24f3-afad-4c48-898a-a57a693b11b7');

        $this->assertEquals(null, $parameter->getRouteName());
        $this->assertEquals('path-value-0d9991c1-0038-4a77-935e-3df9f34c245e', $parameter->getPathValue());
        $this->assertEquals('autowired-value-f6737e3d-8e01-4cbd-8ddb-6a82a01c9d40', $parameter->getAutowiredValue());
        $this->assertEquals('name-53943916-6bf0-43ac-b8c0-a722dd0df0fe', $parameter->getName());
    }

    private function getService(ParametersHolder $holder): ContextParameterProvider
    {
        return new ContextParameterProvider($holder);
    }
}
