<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Internal;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
interface CacheReaderInterface
{
    public function write(string $cacheDir, string $contents): void;

    public function read(string $cacheDir): string;
}
