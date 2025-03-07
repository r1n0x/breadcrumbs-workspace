<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests;

use Random\Randomizer;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class Unused
{
    /**
     * Usage of this means that test does not use it within the actual tests.
     */
    public static function string(): string
    {
        $randomizer = new Randomizer();

        return base64_encode($randomizer->getBytes(16));
    }

    /**
     * Usage of this means that test does not use it within the actual tests.
     */
    public static function array(): array
    {
        $array = [];
        for ($i = 0; $i <= 5; ++$i) {
            $array[] = self::string();
        }

        return $array;
    }

    /**
     * Usage of this means that test does not use it within the actual tests.
     */
    public static function bool(): bool
    {
        return 1 === rand(0, 1);
    }

    /**
     * Usage of this means that test does not use it within the actual tests.
     */
    public static function null(): null
    {
        return null;
    }
}
