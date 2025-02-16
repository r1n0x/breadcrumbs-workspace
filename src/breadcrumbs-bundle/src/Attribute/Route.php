<?php

namespace R1n0x\BreadcrumbsBundle\Attribute;

use Attribute;
use R1n0x\BreadcrumbsBundle\Exception\RuntimeException;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
class Route extends \Symfony\Component\Routing\Attribute\Route
{
    public const EXPRESSION = 'expression';
    public const PARENT_ROUTE = 'parent_route';
    public const PASS_PARAMETERS_TO_EXPRESSION = 'pass_parameters_to_expression';

    public function __construct(
        array|string|null      $path = null,
        ?string                $name = null,
        array                  $requirements = [],
        array                  $options = [],
        array                  $defaults = [],
        ?string                $host = null,
        array|string           $methods = [],
        array|string           $schemes = [],
        ?string                $condition = null,
        ?int                   $priority = null,
        ?string                $locale = null,
        ?string                $format = null,
        ?bool                  $utf8 = null,
        ?bool                  $stateless = null,
        ?string                $env = null,
        private readonly array $breadcrumb = []
    )
    {
        parent::__construct($path,
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
        $this->validateBreadcrumbs();
    }

    /**
     * @return void
     */
    public function validateBreadcrumbs(): void
    {
        if (array_key_exists(self::PARENT_ROUTE, $this->breadcrumb) && !is_scalar($this->breadcrumb[self::PARENT_ROUTE])) {
            throw new RuntimeException(sprintf(
                "Value of breadcrumbs \"%s\" must be a scalar",
                self::PARENT_ROUTE
            ));
        }
        if (array_key_exists(self::EXPRESSION, $this->breadcrumb) && !is_string($this->breadcrumb[self::EXPRESSION])) {
            throw new RuntimeException(sprintf(
                "Value of breadcrumbs \"%s\" must be a string",
                self::EXPRESSION
            ));
        }
        if (array_key_exists(self::PASS_PARAMETERS_TO_EXPRESSION, $this->breadcrumb) && !is_bool($this->breadcrumb[self::PASS_PARAMETERS_TO_EXPRESSION])) {
            throw new RuntimeException(sprintf(
                "Value of breadcrumbs \"%s\" must be a bool",
                self::PASS_PARAMETERS_TO_EXPRESSION
            ));
        }
    }

    public function getBreadcrumb(): array
    {
        return $this->breadcrumb;
    }
}