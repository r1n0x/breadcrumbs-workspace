<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Doubles;

use Random\Randomizer;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
final class Dummy
{
    private static Randomizer $randomizer;

    public static function string(): string
    {
        return 'dummy-' . base64_encode(self::getRandomizer()->getBytes(20));
    }

    public static function integer(): int
    {
        return self::getRandomizer()->getInt(0, 999999999);
    }

    public static function array(): array
    {
        $array = [];
        for ($i = 0; $i <= self::getRandomizer()->getInt(0, 20); ++$i) {
            $array[] = self::string();
        }

        return $array;
    }

    public static function bool(): bool
    {
        return self::getRandomizer()->getInt(3, 10) > 5;
    }

    public static function null(): null
    {
        return null;
    }

    private static function getRandomizer(): Randomizer
    {
        return self::$randomizer ??= new Randomizer();
    }
}
