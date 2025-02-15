<?php

namespace R1n0x\BreadcrumbsBundle\Resolver;

use R1n0x\BreadcrumbsBundle\Exception\ValidationException;
use R1n0x\BreadcrumbsBundle\Generator\LabelGenerator;
use R1n0x\BreadcrumbsBundle\Generator\UrlGenerator;
use R1n0x\BreadcrumbsBundle\Model\Breadcrumb;
use R1n0x\BreadcrumbsBundle\Model\BreadcrumbNode;
use R1n0x\BreadcrumbsBundle\Validator\NodeValidator;
use R1n0x\BreadcrumbsBundle\Validator\ValidationContext;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class BreadcrumbsResolver
{
    public function __construct(
        private readonly BreadcrumbNodesResolver $resolver,
        private readonly UrlGenerator            $urlGenerator,
        private readonly LabelGenerator          $labelGenerator,
        private readonly NodeValidator           $validator
    )
    {
    }

    /**
     * @param Request $request
     * @return array<int, Breadcrumb>
     * @throws ValidationException
     */
    public function getBreadcrumbs(Request $request): array
    {
        $routeName = $request->attributes->getString('_route');
        $node = $this->resolver->getNode($routeName);
        if (!$node) {
            return [];
        }
        $context = new ValidationContext();
        $this->validator->validate($context, [$node], true);
        return array_reverse($this->doBuild($node));
    }

    private function doBuild(?BreadcrumbNode $node): array
    {
        $builtBreadcrumbs = [];
        $builtBreadcrumbs[] = new Breadcrumb(
            $this->labelGenerator->generate($node->getDefinition()),
            $this->urlGenerator->generate($node->getDefinition())
        );
        if ($node->getChild()) {
            $builtBreadcrumbs = [...$builtBreadcrumbs, ...$this->doBuild($node->getChild())];
        }
        return $builtBreadcrumbs;
    }
}