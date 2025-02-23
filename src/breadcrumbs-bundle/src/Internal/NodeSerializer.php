<?php

namespace R1n0x\BreadcrumbsBundle\Internal;

use R1n0x\BreadcrumbsBundle\Attribute\Route;
use R1n0x\BreadcrumbsBundle\Internal\Model\BreadcrumbNode;
use R1n0x\BreadcrumbsBundle\Internal\Model\RootBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\RouteBreadcrumbDefinition;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class NodeSerializer
{
    private const NODE_TYPE = 'node_type';
    private const NODE_TYPE_ROUTE = 'route';
    private const NODE_TYPE_ROOT = 'root';

    /**
     * @param array<int, BreadcrumbNode> $nodes
     */
    public function serialize(array $nodes): string
    {
        /** @var array<int, mixed> $serializedNodes */
        $serializedNodes = [];

        foreach ($nodes as $node) {
            $serializedNodes[] = $this->serializeNode($node);
        }

        /* @phpstan-ignore return.type */
        return json_encode($serializedNodes, JSON_PRETTY_PRINT);
    }

    /**
     * @phpstan-ignore missingType.iterableValue
     */
    public function serializeNode(BreadcrumbNode $node): array
    {
        $definition = $node->getDefinition();

        return [
            /* @phpstan-ignore match.unhandled */
            'definition' => match (get_class($definition)) {
                RouteBreadcrumbDefinition::class => [
                    self::NODE_TYPE => self::NODE_TYPE_ROUTE,
                    'route' => $definition->getRouteName(),
                    Route::EXPRESSION => $definition->getExpression(),
                    Route::PARENT_ROUTE => $definition->getParentRoute(),
                    Route::ROOT => $definition->getRoot(),
                    Route::PASS_PARAMETERS_TO_EXPRESSION => $definition->getPassParametersToExpression(),
                    'parameters' => $definition->getParameters(),
                    'variables' => $definition->getVariables(),
                ],
                RootBreadcrumbDefinition::class => [
                    self::NODE_TYPE => self::NODE_TYPE_ROOT,
                    'route' => $definition->getRouteName(),
                    Route::EXPRESSION => $definition->getExpression(),
                    'name' => $definition->getName(),
                    'variables' => $definition->getVariables(),
                ],
            },
            'parent' => $node->getParent() ? $this->serializeNode($node->getParent()) : null,
        ];
    }

    /**
     * @return array<int, BreadcrumbNode>
     */
    public function deserialize(string $data): array
    {
        /** @var array<int, BreadcrumbNode> $deserializedNodes */
        $deserializedNodes = [];

        $items = json_decode($data, true);
        /* @phpstan-ignore foreach.nonIterable */
        foreach ($items as $item) {
            /* @phpstan-ignore argument.type */
            $deserializedNodes[] = $this->deserializeNode($item);
        }

        return $deserializedNodes;
    }

    /**
     * @phpstan-ignore missingType.iterableValue
     */
    private function deserializeNode(array $item): BreadcrumbNode
    {
        return new BreadcrumbNode(
            /* @phpstan-ignore match.unhandled */
            match ($item['definition'][self::NODE_TYPE]) {
                self::NODE_TYPE_ROUTE => new RouteBreadcrumbDefinition(
                    $item['definition']['route'],
                    $item['definition'][Route::EXPRESSION],
                    $item['definition'][Route::PARENT_ROUTE],
                    $item['definition'][Route::ROOT],
                    $item['definition'][Route::PASS_PARAMETERS_TO_EXPRESSION],
                    $item['definition']['variables'],
                    $item['definition']['parameters']
                ),
                self::NODE_TYPE_ROOT => new RootBreadcrumbDefinition(
                    $item['definition']['route'],
                    $item['definition'][Route::EXPRESSION],
                    $item['definition']['name'],
                    $item['definition']['variables']
                )
            },
            null !== $item['parent'] ? $this->deserializeNode($item['parent']) : null
        );
    }
}
