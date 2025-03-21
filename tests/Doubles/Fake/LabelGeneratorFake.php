<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Doubles\Fake;

use R1n0x\BreadcrumbsBundle\Internal\Generator\LabelGenerator;
use R1n0x\BreadcrumbsBundle\Internal\Provider\LabelVariablesProvider;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
final readonly class LabelGeneratorFake
{
    public static function create(LabelVariablesProvider $provider): LabelGenerator
    {
        return new LabelGenerator(
            new ExpressionLanguage(),
            $provider
        );
    }
}
