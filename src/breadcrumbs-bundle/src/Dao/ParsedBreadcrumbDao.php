<?php

namespace R1n0x\BreadcrumbsBundle\Dao;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class ParsedBreadcrumbDao
{
    public function __construct(
        private readonly string  $label,
        private readonly string  $url
    )
    {
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getUrl(): string
    {
        return $this->url;
    }
}