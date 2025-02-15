<?php

namespace R1n0x\BreadcrumbsBundle\Attribute;

use Attribute;
use R1n0x\BreadcrumbsBundle\Breadcrumb;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
class Route extends \Symfony\Component\Routing\Attribute\Route
{
    public function __construct(
        array|string|null   $path = null,
        ?string             $name = null,
        array               $requirements = [],
        array               $options = [],
        array               $defaults = [],
        ?string             $host = null,
        array|string        $methods = [],
        array|string        $schemes = [],
        ?string             $condition = null,
        ?int                $priority = null,
        ?string             $locale = null,
        ?string             $format = null,
        ?bool               $utf8 = null,
        ?bool               $stateless = null,
        ?string             $env = null,
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
    }

    public function getBreadcrumb(): array
    {
        return $this->breadcrumb;
    }
}