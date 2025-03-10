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
use R1n0x\BreadcrumbsBundle\Internal\Provider\UrlParametersProvider;
use R1n0x\BreadcrumbsBundle\Tests\Provider\ContextParameterProviderProvider;
use R1n0x\BreadcrumbsBundle\Tests\Provider\UrlParametersProviderProvider;
use R1n0x\BreadcrumbsBundle\Tests\Unused;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 *
 * @internal
 */
#[CoversClass(UrlParametersProvider::class)]
#[UsesClass(ParametersHolder::class)]
#[UsesClass(ContextParameterProvider::class)]
#[UsesClass(UndefinedParameterException::class)]
class UrlParametersProviderTest extends TestCase
{
    #[Test]
    #[TestDox('Throws exception, if neither parameter is present nor is optional')]
    public function throwsExceptionIfNeitherParameterIsPresentNorIsOptional(): void
    {
        $this->expectException(UndefinedParameterException::class);
        $this
            ->getService(ContextParameterProviderProvider::empty())
            ->getParameters(new RouteBreadcrumbDefinition(
                'route_name',
                Unused::string(),
                Unused::string(),
                Unused::string(),
                Unused::bool(),
                [
                    new ParameterDefinition(
                        'parameter_name',
                        false,
                        Unused::null()
                    ),
                ],
                Unused::array(),
            ));
    }

    #[Test]
    public function providesParametersForRouteBreadcrumb(): void
    {
        $parameters = $this
            ->getService(ContextParameterProviderProvider::createWithParameters([
                new Parameter(
                    'parameter_name_1',
                    'route_name',
                    'path_value_1',
                    'autowired_value_1'
                ),
                new Parameter(
                    'parameter_name_2',
                    'route_name',
                    'path_value_2',
                    'autowired_value_2'
                ),
            ]))
            ->getParameters(new RouteBreadcrumbDefinition(
                'route_name',
                Unused::string(),
                Unused::string(),
                Unused::string(),
                Unused::bool(),
                [
                    new ParameterDefinition(
                        'parameter_name_1',
                        Unused::bool(),
                        Unused::null()
                    ),
                    new ParameterDefinition(
                        'parameter_name_2',
                        Unused::bool(),
                        Unused::null()
                    ),
                ],
                Unused::array(),
            ));

        $this->assertArrayHasKey('parameter_name_1', $parameters);
        $this->assertArrayHasKey('parameter_name_2', $parameters);
        $this->assertEquals('path_value_1', $parameters['parameter_name_1']);
        $this->assertEquals('path_value_2', $parameters['parameter_name_2']);
    }

    #[Test]
    #[TestDox('Fallbacks to optional value, if parameter is not present in parameters')]
    public function fallbacksToOptionalValueIfParameterNotPresentInParameters(): void
    {
        $parameters = $this
            ->getService(ContextParameterProviderProvider::empty())
            ->getParameters(new RouteBreadcrumbDefinition(
                'route_name',
                Unused::string(),
                Unused::string(),
                Unused::string(),
                Unused::bool(),
                [
                    new ParameterDefinition(
                        'parameter_name',
                        true,
                        'optional_value'
                    ),
                ],
                Unused::array(),
            ));

        $this->assertArrayHasKey('parameter_name', $parameters);
        $this->assertEquals('optional_value', $parameters['parameter_name']);
    }

    private function getService(ContextParameterProvider $provider): UrlParametersProvider
    {
        return UrlParametersProviderProvider::create($provider);
    }
}
