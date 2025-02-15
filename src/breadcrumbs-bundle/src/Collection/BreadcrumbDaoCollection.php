<?php

namespace R1n0x\BreadcrumbsBundle\Collection;

use R1n0x\BreadcrumbsBundle\Dao\BreadcrumbDao;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class BreadcrumbDaoCollection
{
    /**
     * @param array<int, BreadcrumbDao> $elements
     */
    public function __construct(
        private readonly array $elements = []
    )
    {
    }

    /**
     * @return array<int, BreadcrumbDao>
     */
    public function all(): array
    {
        return $this->elements;
    }
}