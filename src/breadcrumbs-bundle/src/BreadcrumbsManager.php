<?php

namespace R1n0x\BreadcrumbsBundle;

use R1n0x\BreadcrumbsBundle\Exception\ValidationException;
use R1n0x\BreadcrumbsBundle\Internal\BreadcrumbsBuilder;
use R1n0x\BreadcrumbsBundle\Internal\Holder\ParametersHolder;
use R1n0x\BreadcrumbsBundle\Internal\Holder\VariablesHolder;
use R1n0x\BreadcrumbsBundle\Internal\Model\Parameter;
use R1n0x\BreadcrumbsBundle\Internal\Model\Variable;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class BreadcrumbsManager
{
    public function __construct(
        private readonly ParametersHolder $parametersHolder,
        private readonly VariablesHolder $variablesHolder,
        private readonly BreadcrumbsBuilder $builder
    ) {}

    public function setParameter(string $name, ?string $value, ?string $routeName = null): static
    {
        $this->parametersHolder->set(new Parameter($name, $value, $routeName));

        return $this;
    }

    public function setVariable(string $name, mixed $value, ?string $routeName = null): static
    {
        $this->variablesHolder->set(new Variable($name, $value, $routeName));

        return $this;
    }

    /**
     * @return array<int, Breadcrumb>
     *
     * @throws ValidationException
     */
    public function build(Request $request): array
    {
        return $this->builder->getBreadcrumbs($request);
    }
}
