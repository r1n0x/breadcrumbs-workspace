<?php

namespace R1n0x\BreadcrumbsBundle;

use R1n0x\BreadcrumbsBundle\Exception\FileAccessException;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class CacheReader
{
    public function write(string $cacheDir, string $contents): void
    {
        $status = file_put_contents($this->getFilePath($cacheDir), $contents);
        if ($status === false) {
            throw new FileAccessException('Breadcrumbs couldn\'t be written to cache file');
        }

    }

    public function read(string $cacheDir): string
    {
        $contents = @file_get_contents($this->getFilePath($cacheDir));
        if ($contents === false) {
            throw new FileAccessException('Breadcrumbs couldn\'t be read from cache file');
        }
        return $contents;
    }

    private function getFilePath(string $cacheDir): string
    {
        return sprintf('%s/%s', $cacheDir, 'breadcrumbs.json');
    }
}