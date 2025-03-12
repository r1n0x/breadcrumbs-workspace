<?php

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
use R1n0x\BreadcrumbsBundle\Tests\Doubles\Dummy;
use R1n0x\BreadcrumbsBundle\Tests\Doubles\Fake\ContextParameterProviderFake;
use R1n0x\BreadcrumbsBundle\Tests\Doubles\Fake\UrlParametersProviderFake;

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
            ->getService(ContextParameterProviderFake::createWithParameters())
            ->getParameters(new RouteBreadcrumbDefinition(
                'route',
                Dummy::string(),
                Dummy::string(),
                Dummy::string(),
                Dummy::bool(),
                [
                    new ParameterDefinition(
                        'parameter',
                        false,
                        Dummy::null()
                    ),
                ],
                Dummy::array(),
            ));
    }

    #[Test]
    public function providesParameters(): void
    {
        $parameters = $this
            ->getService(ContextParameterProviderFake::createWithParameters([
                new Parameter(
                    'parameter_1',
                    'route',
                    'path_value_1',
                    'autowired_value_1'
                ),
                new Parameter(
                    'parameter_2',
                    'route',
                    'path_value_2',
                    'autowired_value_2'
                ),
            ]))
            ->getParameters(new RouteBreadcrumbDefinition(
                'route',
                Dummy::string(),
                Dummy::string(),
                Dummy::string(),
                Dummy::bool(),
                [
                    new ParameterDefinition(
                        'parameter_1',
                        Dummy::bool(),
                        Dummy::null()
                    ),
                    new ParameterDefinition(
                        'parameter_2',
                        Dummy::bool(),
                        Dummy::null()
                    ),
                ],
                Dummy::array(),
            ));

        $this->assertArrayHasKey('parameter_1', $parameters);
        $this->assertArrayHasKey('parameter_2', $parameters);
        $this->assertEquals('path_value_1', $parameters['parameter_1']);
        $this->assertEquals('path_value_2', $parameters['parameter_2']);
    }

    #[Test]
    #[TestDox('Fallbacks to optional value, if parameter is not present in holder')]
    public function fallbacksToOptionalValueIfParameterNotPresentInHolder(): void
    {
        $parameters = $this
            ->getService(ContextParameterProviderFake::createWithParameters())
            ->getParameters(new RouteBreadcrumbDefinition(
                'route',
                Dummy::string(),
                Dummy::string(),
                Dummy::string(),
                Dummy::bool(),
                [
                    new ParameterDefinition(
                        'parameter',
                        true,
                        'value'
                    ),
                ],
                Dummy::array(),
            ));

        $this->assertArrayHasKey('parameter', $parameters);
        $this->assertEquals('value', $parameters['parameter']);
    }

    private function getService(ContextParameterProvider $provider): UrlParametersProvider
    {
        return UrlParametersProviderFake::create($provider);
    }
}
