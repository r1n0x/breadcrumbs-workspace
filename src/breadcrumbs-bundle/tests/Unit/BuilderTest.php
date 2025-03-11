<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Unit;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use R1n0x\BreadcrumbsBundle\Builder;
use R1n0x\BreadcrumbsBundle\Internal\CacheReaderInterface;
use R1n0x\BreadcrumbsBundle\Internal\Generator\LabelGenerator;
use R1n0x\BreadcrumbsBundle\Internal\Generator\UrlGenerator;
use R1n0x\BreadcrumbsBundle\Internal\Model\BreadcrumbNode;
use R1n0x\BreadcrumbsBundle\Internal\Model\RootBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\RouteBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\NodeSerializer;
use R1n0x\BreadcrumbsBundle\Internal\Provider\ContextParameterProvider;
use R1n0x\BreadcrumbsBundle\Internal\Provider\ContextVariableProvider;
use R1n0x\BreadcrumbsBundle\Internal\Provider\LabelVariablesProvider;
use R1n0x\BreadcrumbsBundle\Internal\Provider\UrlParametersProvider;
use R1n0x\BreadcrumbsBundle\Internal\Resolver\NodesResolver;
use R1n0x\BreadcrumbsBundle\Internal\Resolver\ParametersResolver;
use R1n0x\BreadcrumbsBundle\Internal\Validator\Node\NodeContextValidator;
use R1n0x\BreadcrumbsBundle\Internal\Validator\Node\ValidationContext;
use R1n0x\BreadcrumbsBundle\Tests\Doubles\Dummy;
use R1n0x\BreadcrumbsBundle\Tests\Doubles\Fake\ContextParameterProviderFake;
use R1n0x\BreadcrumbsBundle\Tests\Doubles\Fake\ContextVariableProviderFake;
use R1n0x\BreadcrumbsBundle\Tests\Doubles\Fake\LabelGeneratorFake;
use R1n0x\BreadcrumbsBundle\Tests\Doubles\Fake\LabelVariablesProviderFake;
use R1n0x\BreadcrumbsBundle\Tests\Doubles\Fake\NodeContextValidatorFake;
use R1n0x\BreadcrumbsBundle\Tests\Doubles\Fake\NodesResolverFake;
use R1n0x\BreadcrumbsBundle\Tests\Doubles\Fake\UrlGeneratorFake;
use R1n0x\BreadcrumbsBundle\Tests\Doubles\Fake\UrlParametersProviderFake;
use R1n0x\BreadcrumbsBundle\Tests\Stub\CacheReaderStub;
use R1n0x\BreadcrumbsBundle\Tests\Stub\RouterStub;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 *
 * @internal
 */
#[CoversClass(Builder::class)]
#[UsesClass(LabelGenerator::class)]
#[UsesClass(UrlGenerator::class)]
#[UsesClass(NodeSerializer::class)]
#[UsesClass(ContextParameterProvider::class)]
#[UsesClass(ContextVariableProvider::class)]
#[UsesClass(UrlParametersProvider::class)]
#[UsesClass(NodesResolver::class)]
#[UsesClass(NodeContextValidator::class)]
#[UsesClass(LabelVariablesProvider::class)]
#[UsesClass(ParametersResolver::class)]
#[UsesClass(ValidationContext::class)]
class BuilderTest extends TestCase
{
    #[Test]
    public function buildsBreadcrumbsForRequest(): void
    {
        $contextParameterProvider = ContextParameterProviderFake::createWithParameters();

        $breadcrumbs = $this
            ->getService(
                CacheReaderStub::create()
                    ->addNode(new BreadcrumbNode(
                        new RouteBreadcrumbDefinition(
                            'route_1',
                            "'expression_1'",
                            Dummy::string(),
                            Dummy::string(),
                            Dummy::bool(),
                            [],
                            []
                        ),
                        new BreadcrumbNode(
                            new RouteBreadcrumbDefinition(
                                'route_2',
                                "'expression_2'",
                                Dummy::string(),
                                Dummy::string(),
                                Dummy::bool(),
                                [],
                                []
                            ),
                            new BreadcrumbNode(
                                new RootBreadcrumbDefinition(
                                    'route_3',
                                    "'expression_3'",
                                    Dummy::string(),
                                    []
                                ),
                                null
                            )
                        )
                    )),
                RouterStub::create()
                    ->addRouteStub('route_1', '/route/1')
                    ->addRouteStub('route_2', '/route/2')
                    ->addRouteStub('route_3', '/route/3'),
                $contextParameterProvider,
                ContextVariableProviderFake::createWithParameterProvider($contextParameterProvider)
            )
            ->build(new Request(attributes: ['_route' => 'route_1']));

        $this->assertCount(3, $breadcrumbs);

        $this->assertEquals('/route/3', $breadcrumbs[0]->getUrl());
        $this->assertEquals('expression_3', $breadcrumbs[0]->getLabel());

        $this->assertEquals('/route/2', $breadcrumbs[1]->getUrl());
        $this->assertEquals('expression_2', $breadcrumbs[1]->getLabel());

        $this->assertEquals('/route/1', $breadcrumbs[2]->getUrl());
        $this->assertEquals('expression_1', $breadcrumbs[2]->getLabel());
    }

    #[Test]
    #[TestDox("Builds, if request route doesn't have a breadcrumb")]
    public function buildsIfRequestRouteDoesntHaveABreadcrumb(): void
    {
        $contextParameterProvider = ContextParameterProviderFake::createWithParameters();

        $breadcrumbs = $this
            ->getService(
                CacheReaderStub::create(),
                RouterStub::create(),
                $contextParameterProvider,
                ContextVariableProviderFake::createWithParameterProvider($contextParameterProvider)
            )
            ->build(new Request(attributes: ['_route' => 'route_1']));

        $this->assertCount(0, $breadcrumbs);
    }

    private function getService(
        CacheReaderInterface $cacheReader,
        RouterInterface $router,
        ContextParameterProvider $contextParameterProvider,
        ContextVariableProvider $contextVariableProvider
    ): Builder {
        return new Builder(
            NodesResolverFake::create($cacheReader),
            UrlGeneratorFake::create(
                $router,
                UrlParametersProviderFake::create($contextParameterProvider)
            ),
            LabelGeneratorFake::create(LabelVariablesProviderFake::create($contextVariableProvider)),
            NodeContextValidatorFake::create($contextVariableProvider, $contextParameterProvider)
        );
    }
}
