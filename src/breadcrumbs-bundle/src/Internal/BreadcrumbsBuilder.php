<?php

namespace R1n0x\BreadcrumbsBundle\Internal;

use R1n0x\BreadcrumbsBundle\Breadcrumb;
use R1n0x\BreadcrumbsBundle\Exception\ValidationException;
use R1n0x\BreadcrumbsBundle\Internal\Generator\LabelGenerator;
use R1n0x\BreadcrumbsBundle\Internal\Generator\UrlGenerator;
use R1n0x\BreadcrumbsBundle\Internal\Model\BreadcrumbNode;
use R1n0x\BreadcrumbsBundle\Internal\Resolver\NodesResolver;
use R1n0x\BreadcrumbsBundle\Internal\Validator\Node\NodeValidator;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class BreadcrumbsBuilder
{
    public function __construct(
        private readonly NodesResolver $resolver,
        private readonly UrlGenerator $urlGenerator,
        private readonly LabelGenerator $labelGenerator,
        private readonly NodeValidator $validator
    ) {}

    /**
     * @return array<int, Breadcrumb>
     *
     * @throws ValidationException
     */
    public function getBreadcrumbs(Request $request): array
    {
        $routeName = $request->attributes->getString('_route');
        $node = $this->resolver->get($routeName);
        if (!$node) {
            return [];
        }
        $this->validator->validate($node);

        return array_reverse($this->doBuild($node));
    }

    /**
     * @return array<int, Breadcrumb>
     */
    private function doBuild(BreadcrumbNode $node): array
    {
        $breadcrumbs = [];
        $breadcrumbs[] = new Breadcrumb(
            $this->labelGenerator->generate($node->getDefinition()),
            $this->urlGenerator->generate($node->getDefinition())
        );
        $parent = $node->getParent();
        if ($parent) {
            $breadcrumbs = array_merge($breadcrumbs, $this->doBuild($parent));
        }

        return $breadcrumbs;
    }
}
