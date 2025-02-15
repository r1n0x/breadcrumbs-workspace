<?php

namespace R1n0x\BreadcrumbsBundle\Validator;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class ValidationContext
{
    public const TYPE_PARAMETER = 'parameter';
    public const TYPE_VARIABLE = 'variable';
    public const TYPE = 'type';
    public const ROUTE_NAME = 'route_name';
    public const NAME = 'name';

    private array $errors = [];

    public function addParameterViolation(string $routeName, string $parameterName): void
    {
        $this->errors[] = [
            ValidationContext::TYPE => ValidationContext::TYPE_PARAMETER,
            ValidationContext::ROUTE_NAME => $routeName,
            ValidationContext::NAME => $parameterName
        ];
    }

    public function addVariableViolation(string $routeName, string $variableName): void
    {
        $this->errors[] = [
            ValidationContext::TYPE => ValidationContext::TYPE_VARIABLE,
            ValidationContext::ROUTE_NAME => $routeName,
            ValidationContext::NAME => $variableName
        ];
    }

    public function hasErrors(): bool
    {
        return count($this->errors) > 0;
    }

    /**
     * @return array
     */
    public function getGroupedForRoutes(): array
    {
        $errors = [];
        foreach ($this->errors as $error) {
            $routeName = $error[ValidationContext::ROUTE_NAME];
            $key = $routeName . '.' . $error[ValidationContext::TYPE];
            $name = $error[ValidationContext::NAME];
            if (isset($errors[$key])) {
                $errors[$key][ValidationContext::NAME] = [
                    ...$errors[$key][ValidationContext::NAME],
                    $name
                ];
                continue;
            }
            $errors[$key] = [
                ValidationContext::TYPE => $error[ValidationContext::TYPE],
                ValidationContext::ROUTE_NAME => $routeName,
                ValidationContext::NAME => [$name]
            ];
        }

        usort($errors, fn($error) => $error[ValidationContext::TYPE] === ValidationContext::TYPE_VARIABLE);

        return array_values($errors);
    }
}