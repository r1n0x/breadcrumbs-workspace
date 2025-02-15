<?php

namespace R1n0x\BreadcrumbsBundle;

use R1n0x\BreadcrumbsBundle\Collection\BreadcrumbDaoCollection;
use R1n0x\BreadcrumbsBundle\Dao\ParsedBreadcrumbDao;
use R1n0x\BreadcrumbsBundle\Exception\RouteNotFoundException;
use R1n0x\BreadcrumbsBundle\Exception\ValidationException;
use R1n0x\BreadcrumbsBundle\Generator\LabelGenerator;
use R1n0x\BreadcrumbsBundle\Generator\UrlGenerator;
use R1n0x\BreadcrumbsBundle\Resolver\BreadcrumbsResolver;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class BreadcrumbsBuilder
{
    public function __construct(
        private readonly BreadcrumbsResolver $resolver,
        private readonly UrlGenerator        $urlGenerator,
        private readonly LabelGenerator      $labelGenerator,
        private readonly Validator $validator
    )
    {
    }

    /**
     * @param Request $request
     * @return array<int, ParsedBreadcrumbDao>
     * @throws ValidationException
     * @throws RouteNotFoundException
     */
    public function build(Request $request): array
    {
        $routeName = $request->attributes->getString('_route');
        $parsedBreadcrumbs = [];
        $breadcrumbs = $this->resolver->resolve($routeName);
        $this->validator->validate(new BreadcrumbDaoCollection($breadcrumbs));
        foreach ($breadcrumbs as $breadcrumb) {
            $parsedBreadcrumbs[] = new ParsedBreadcrumbDao(
                $this->labelGenerator->generate($breadcrumb),
                $this->urlGenerator->generate($breadcrumb)
            );
        }
        return $parsedBreadcrumbs;
    }
}