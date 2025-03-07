<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Internal;

use R1n0x\BreadcrumbsBundle\Exception\FileAccessException;

/**
 * @codeCoverageIgnore
 *
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class CacheReader
{
    /**
     * @throws FileAccessException
     */
    public function write(string $cacheDir, string $contents): void
    {
        $status = file_put_contents($this->getFilePath($cacheDir), $contents);
        if (false === $status) {
            throw new FileAccessException('Breadcrumbs couldn\'t be written to cache file');
        }
    }

    /**
     * @throws FileAccessException
     */
    public function read(string $cacheDir): string
    {
        $contents = @file_get_contents($this->getFilePath($cacheDir));
        if (false === $contents) {
            throw new FileAccessException('Breadcrumbs couldn\'t be read from cache file');
        }

        return $contents;
    }

    private function getFilePath(string $cacheDir): string
    {
        return sprintf('%s/%s', $cacheDir, 'breadcrumbs.json');
    }
}
