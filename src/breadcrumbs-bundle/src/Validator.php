<?php

namespace R1n0x\BreadcrumbsBundle;

use R1n0x\BreadcrumbsBundle\Collection\BreadcrumbDaoCollection;
use R1n0x\BreadcrumbsBundle\Exception\ValidationException;
use R1n0x\BreadcrumbsBundle\Storage\ExpressionVariablesStorage;
use R1n0x\BreadcrumbsBundle\Storage\RouterParametersStorage;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class Validator
{
    public function __construct(
        private readonly RouterParametersStorage $parametersStorage,
        private readonly ExpressionVariablesStorage $variablesStorage
    )
    {
    }


    /**
     * @throws ValidationException
     */
    public function validate(BreadcrumbDaoCollection $breadcrumbs): void
    {
        $missingVariables = [];
        $missingParameters = [];

        foreach ($breadcrumbs->all() as $breadcrumb) {
            $missingVariables = [...$missingVariables, ...$this->getMissingKeys($breadcrumb->getVariables(), $this->variablesStorage->all())];
            $missingParameters = [...$missingParameters, ...$this->getMissingKeys($breadcrumb->getParameters(), $this->parametersStorage->all())];
        }

        $message = null;
        if(count($missingVariables) > 0 ) {
            $message = 'Missing variables: ' . implode(', ', $missingVariables);
        }
        if(count($missingParameters) > 0) {
            $message = $message . ' Missing parameters: ' . implode(', ', $missingParameters);
        }
        if($message) {
            throw new ValidationException($message);
        }
    }

    protected function getMissingKeys(array $keys, array $array): array
    {
        return array_keys(array_diff_key(array_flip($keys), $array));
    }
}