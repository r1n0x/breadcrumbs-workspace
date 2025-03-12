<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Attribute;

use Attribute;

/**
 * @codeCoverageIgnore
 *
 * @author r1n0x <r1n0x-dev@proton.me>
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
class Route extends \Symfony\Component\Routing\Attribute\Route
{
    public const string EXPRESSION = 'expression';
    public const string PARENT_ROUTE = 'parent_route';
    public const string PASS_PARAMETERS_TO_EXPRESSION = 'pass_parameters_to_expression';
    public const string ROOT = 'root';

    public function __construct(
        null|array|string $path = null,
        ?string $name = null,
        array $requirements = [],
        array $options = [],
        array $defaults = [],
        ?string $host = null,
        array|string $methods = [],
        array|string $schemes = [],
        ?string $condition = null,
        ?int $priority = null,
        ?string $locale = null,
        ?string $format = null,
        ?bool $utf8 = null,
        ?bool $stateless = null,
        ?string $env = null,
        /** @var array<string, mixed> */
        private readonly array $breadcrumb = []
    ) {
        parent::__construct(
            $path,
            $name,
            $requirements,
            $options,
            $defaults,
            $host,
            $methods,
            $schemes,
            $condition,
            $priority,
            $locale,
            $format,
            $utf8,
            $stateless,
            $env
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function getBreadcrumb(): array
    {
        return $this->breadcrumb;
    }
}
