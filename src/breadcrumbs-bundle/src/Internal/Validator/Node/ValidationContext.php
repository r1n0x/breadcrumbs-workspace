<?php

namespace R1n0x\BreadcrumbsBundle\Internal\Validator\Node;

use R1n0x\BreadcrumbsBundle\Internal\Model\Violation\Error;
use R1n0x\BreadcrumbsBundle\Internal\Model\Violation\ErrorType;
use R1n0x\BreadcrumbsBundle\Internal\Model\Violation\RootError;
use R1n0x\BreadcrumbsBundle\Internal\Model\Violation\RouteError;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class ValidationContext
{
    /** @var array<int, RouteError> */
    private array $routeErrors = [];

    /** @var array<int, RootError> */
    private array $rootErrors = [];

    public function addRouteParameterViolation(string $routeName, string $parameterName): void
    {
        $this->getRouteViolation($routeName, ErrorType::Parameter)->addName($parameterName);
    }

    public function addRouteVariableViolation(string $routeName, string $variableName): void
    {
        $this->getRouteViolation($routeName, ErrorType::Variable)->addName($variableName);
    }

    public function addRootVariableViolation(string $name, string $variableName): void
    {
        $this->getRootViolation($name, ErrorType::Variable)->addName($variableName);
    }

    public function hasErrors(): bool
    {
        return (count($this->routeErrors) + count($this->rootErrors)) > 0;
    }

    /**
     * @return array<int, Error>
     */
    public function getErrors(): array
    {
        return array_merge($this->routeErrors, $this->routeErrors);
    }

    private function getRouteViolation(string $routeName, ErrorType $type): RouteError
    {
        foreach ($this->routeErrors as $error) {
            if ($error->getRouteName() === $routeName && $error->getType() === $type) {
                return $error;
            }
        }
        $error = new RouteError(
            $type,
            $routeName,
        );
        $this->routeErrors[] = $error;

        return $error;
    }

    private function getRootViolation(string $name, ErrorType $type): RootError
    {
        foreach ($this->rootErrors as $error) {
            if ($error->getName() === $name && $error->getType() === $type) {
                return $error;
            }
        }
        $error = new RootError(
            $type,
            $name,
        );
        $this->rootErrors[] = $error;

        return $error;
    }
}
