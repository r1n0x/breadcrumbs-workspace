<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Internal\Generator;

use R1n0x\BreadcrumbsBundle\Exception\LabelGenerationException;
use R1n0x\BreadcrumbsBundle\Exception\UndefinedVariableException;
use R1n0x\BreadcrumbsBundle\Internal\Model\BreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Provider\LabelVariablesProvider;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Throwable;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
final readonly class LabelGenerator
{
    public function __construct(
        private ExpressionLanguage $expressionLanguage,
        private LabelVariablesProvider $provider
    ) {}

    /**
     * @throws LabelGenerationException
     * @throws UndefinedVariableException
     */
    public function generate(BreadcrumbDefinition $definition): string
    {
        try {
            /* @phpstan-ignore cast.string */
            return (string) $this->expressionLanguage->evaluate(
                $definition->getExpression(),
                $this->provider->getVariables($definition)
            );
        } catch (UndefinedVariableException $e) {
            throw $e;
        } catch (Throwable $e) {
            throw new LabelGenerationException($definition, previous: $e);
        }
    }
}
