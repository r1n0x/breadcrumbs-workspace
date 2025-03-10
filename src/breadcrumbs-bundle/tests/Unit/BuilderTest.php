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
use R1n0x\BreadcrumbsBundle\Tests\Provider\ContextParameterProviderProvider;
use R1n0x\BreadcrumbsBundle\Tests\Provider\ContextVariableProviderProvider;
use R1n0x\BreadcrumbsBundle\Tests\Provider\LabelGeneratorProvider;
use R1n0x\BreadcrumbsBundle\Tests\Provider\LabelVariablesProviderProvider;
use R1n0x\BreadcrumbsBundle\Tests\Provider\NodeContextValidatorProvider;
use R1n0x\BreadcrumbsBundle\Tests\Provider\NodesResolverProvider;
use R1n0x\BreadcrumbsBundle\Tests\Provider\UrlGeneratorProvider;
use R1n0x\BreadcrumbsBundle\Tests\Provider\UrlParametersProviderProvider;
use R1n0x\BreadcrumbsBundle\Tests\Stub\CacheReaderStub;
use R1n0x\BreadcrumbsBundle\Tests\Stub\RouterStub;
use R1n0x\BreadcrumbsBundle\Tests\Unused;
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
        $contextParameterProvider = ContextParameterProviderProvider::createWithParameters();
        $breadcrumbs = $this
            ->getService(
                CacheReaderStub::create()
                    ->addNode(new BreadcrumbNode(
                        new RouteBreadcrumbDefinition(
                            'route_1',
                            "'expression_1'",
                            Unused::string(),
                            Unused::string(),
                            Unused::bool(),
                            [],
                            []
                        ),
                        new BreadcrumbNode(
                            new RouteBreadcrumbDefinition(
                                'route_2',
                                "'expression_2'",
                                Unused::string(),
                                Unused::string(),
                                Unused::bool(),
                                [],
                                []
                            ),
                            new BreadcrumbNode(
                                new RootBreadcrumbDefinition(
                                    'route_3',
                                    "'expression_3'",
                                    Unused::string(),
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
                ContextVariableProviderProvider::createWithVariables(
                    [],
                    $contextParameterProvider
                )
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
    #[TestDox("Returns empty array, if current route doesn't have a breadcrumb")]
    public function returnsEmptyArrayIfCurrentRouteDoesntHaveABreadcrumb(): void
    {
        $contextParameterProvider = ContextParameterProviderProvider::createWithParameters();
        $breadcrumbs = $this
            ->getService(
                CacheReaderStub::create(),
                RouterStub::create(),
                $contextParameterProvider,
                ContextVariableProviderProvider::createWithVariables(
                    [],
                    $contextParameterProvider
                )
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
            NodesResolverProvider::create($cacheReader),
            UrlGeneratorProvider::create(
                $router,
                UrlParametersProviderProvider::create($contextParameterProvider)
            ),
            LabelGeneratorProvider::create(LabelVariablesProviderProvider::create($contextVariableProvider)),
            NodeContextValidatorProvider::create($contextVariableProvider, $contextParameterProvider)
        );
    }
}
