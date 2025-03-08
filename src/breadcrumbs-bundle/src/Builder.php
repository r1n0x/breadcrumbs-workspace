<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle;

use R1n0x\BreadcrumbsBundle\Exception\LabelGenerationException;
use R1n0x\BreadcrumbsBundle\Exception\UndefinedParameterException;
use R1n0x\BreadcrumbsBundle\Exception\UndefinedVariableException;
use R1n0x\BreadcrumbsBundle\Exception\ValidationException;
use R1n0x\BreadcrumbsBundle\Internal\Generator\LabelGenerator;
use R1n0x\BreadcrumbsBundle\Internal\Generator\UrlGenerator;
use R1n0x\BreadcrumbsBundle\Internal\Model\BreadcrumbNode;
use R1n0x\BreadcrumbsBundle\Internal\Resolver\NodesResolver;
use R1n0x\BreadcrumbsBundle\Internal\Validator\Node\NodeContextValidator;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class Builder
{
    public function __construct(
        private readonly NodesResolver $resolver,
        private readonly UrlGenerator $urlGenerator,
        private readonly LabelGenerator $labelGenerator,
        private readonly NodeContextValidator $validator
    ) {}

    /**
     * @return array<int, Breadcrumb>
     *
     * @throws ValidationException
     * @throws LabelGenerationException
     * @throws UndefinedParameterException
     * @throws UndefinedVariableException
     */
    public function build(Request $request): array
    {
        $routeName = $request->attributes->getString('_route');
        $node = $this->resolver->get($routeName);
        if (null === $node) {
            return [];
        }
        $this->validator->validate($node);

        return array_reverse($this->doBuild($node));
    }

    /**
     * @return array<int, Breadcrumb>
     *
     * @throws LabelGenerationException
     * @throws UndefinedParameterException
     * @throws UndefinedVariableException
     */
    private function doBuild(BreadcrumbNode $node): array
    {
        $breadcrumbs = [];
        $breadcrumbs[] = new Breadcrumb(
            $this->labelGenerator->generate($node->getDefinition()),
            $this->urlGenerator->generate($node->getDefinition())
        );
        $parent = $node->getParent();
        if (null !== $parent) {
            $breadcrumbs = array_merge($breadcrumbs, $this->doBuild($parent));
        }

        return $breadcrumbs;
    }
}
