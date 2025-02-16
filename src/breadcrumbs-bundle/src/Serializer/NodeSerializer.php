<?php

namespace R1n0x\BreadcrumbsBundle\Serializer;

use R1n0x\BreadcrumbsBundle\Attribute\Route;
use R1n0x\BreadcrumbsBundle\Model\BreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Model\BreadcrumbNode;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class NodeSerializer
{
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
        return [
            'node' => [
                'route' => $node->getDefinition()->getRouteName(),
                Route::EXPRESSION => $node->getDefinition()->getExpression(),
                Route::PARENT_ROUTE => $node->getDefinition()->getParentRoute(),
                Route::PASS_PARAMETERS_TO_EXPRESSION => $node->getDefinition()->getPassParametersToExpression(),
                'parameters' => $node->getDefinition()->getParameters(),
                'variables' => $node->getDefinition()->getVariables()
            ],
            'child' => $node->getChild() ? $this->serializeNode($node->getChild()) : null
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
            new BreadcrumbDefinition(
                $item['node']['route'],
                $item['node'][Route::EXPRESSION],
                $item['node'][Route::PARENT_ROUTE],
                $item['node'][Route::PASS_PARAMETERS_TO_EXPRESSION],
                $item['node']['variables'],
                $item['node']['parameters']
            ),
            $item['child'] !== null ? $this->deserializeNode($item['child']) : null
        );
    }
}