<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Doubles\Fake;

use R1n0x\BreadcrumbsBundle\Internal\Generator\UrlGenerator;
use R1n0x\BreadcrumbsBundle\Internal\Provider\UrlParametersProvider;
use Symfony\Component\Routing\RouterInterface;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
final readonly class UrlGeneratorFake
{
    public static function create(
        RouterInterface $router,
        UrlParametersProvider $provider,
    ): UrlGenerator {
        return new UrlGenerator(
            $router,
            $provider
        );
    }
}
