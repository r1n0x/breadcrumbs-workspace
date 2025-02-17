<?php

namespace R1n0x\BreadcrumbsBundle\Serializer;

use R1n0x\BreadcrumbsBundle\Attribute\Route;
use R1n0x\BreadcrumbsBundle\Model\BreadcrumbNode;
use R1n0x\BreadcrumbsBundle\Model\RootBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Model\RouteBreadcrumbDefinition;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class NodeSerializer
{
    private const NODE_TYPE = "node_type";
    private const NODE_TYPE_ROUTE = "route";
    private const NODE_TYPE_ROOT = "root";

    /**
     * @param array<int, BreadcrumbNode> $nodes
     * @return string
     */
    public function serialize(array $nodes): string
    {
        $serializedNodes = [];

        foreach ($nodes as $node) {
            $serializedNodes[] = $this->serializeNode($node);
        }

        return json_encode($serializedNodes, JSON_PRETTY_PRINT);
    }

    /**
     * @param BreadcrumbNode $node
     * @return array
     */
    public function serializeNode(BreadcrumbNode $node): array
    {
        $definition = $node->getDefinition();
        return [
            'definition' => match (get_class($definition)) {
                RouteBreadcrumbDefinition::class => [
                    self::NODE_TYPE => self::NODE_TYPE_ROUTE,
                    'route' => $definition->getRouteName(),
                    Route::EXPRESSION => $definition->getExpression(),
                    Route::PARENT_ROUTE => $definition->getParentRoute(),
                    Route::ROOT => $definition->getRoot(),
                    Route::PASS_PARAMETERS_TO_EXPRESSION => $definition->getPassParametersToExpression(),
                    'parameters' => $definition->getParameters(),
                    'variables' => $definition->getVariables()
                ],
                RootBreadcrumbDefinition::class => [
                    self::NODE_TYPE => self::NODE_TYPE_ROOT,
                    'route' => $definition->getRouteName(),
                    Route::EXPRESSION => $definition->getExpression(),
                    'name' => $definition->getName(),
                    'variables' => $definition->getVariables()
                ],
            },
            'parent' => $node->getParent() ? $this->serializeNode($node->getParent()) : null
        ];
    }

    /**
     * @param string $data
     * @return array<int, BreadcrumbNode>
     */
    public function deserialize(string $data): array
    {
        $deserializedNodes = [];

        foreach (json_decode($data, true) as $item) {
            $deserializedNodes[] = $this->deserializeNode($item);
        }

        return $deserializedNodes;
    }

    private function deserializeNode(array $item): BreadcrumbNode
    {
        return new BreadcrumbNode(
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
            $item['parent'] !== null ? $this->deserializeNode($item['parent']) : null
        );
    }
}