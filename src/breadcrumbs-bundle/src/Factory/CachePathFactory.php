<?php

namespace R1n0x\BreadcrumbsBundle\Factory;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class CachePathFactory
{
    public function getFileCachePath(string $cacheDir): string
    {
        return sprintf(
            '%s/%s',
            $cacheDir,
            'breadcrumbs.json'
        );
    }
}