<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Command;

use R1n0x\BreadcrumbsBundle\Internal\Model\BreadcrumbNode;
use R1n0x\BreadcrumbsBundle\Internal\Model\ParameterDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\RootBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\RouteBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Resolver\NodesResolver;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @codeCoverageIgnore
 *
 * @author r1n0x <r1n0x-dev@proton.me>
 */
#[AsCommand(
    name: 'debug:breadcrumbs',
    description: 'Display breadcrumb trees for an application'
)]
class DebugBreadcrumbsCommand extends Command
{
    public function __construct(
        private readonly NodesResolver $resolver
    ) {
        parent::__construct();
    }

    public function printRouteDefinition(OutputInterface $output, string $prefix, RouteBreadcrumbDefinition $definition, int $level): void
    {
        $output->writeln($prefix . "----> \033[0;32m" . $definition->getRouteName() . "\033[0m (" . $level . ')');
        $output->writeln($prefix . '      Expression: "' . $definition->getExpression() . '"');
        $output->writeln($prefix . '      Parameters: [' . implode(', ', array_map(function (ParameterDefinition $definition) {
            if ($definition->isOptional()) {
                return sprintf('%s (optional - default value: "%s")', $definition->getName(), $definition->getOptionalValue() ?? 'null');
            }

            return $definition->getName();
        }, $definition->getParameters())) . ']');
    }

    public function printRootDefinition(OutputInterface $output, string $prefix, int $level, RootBreadcrumbDefinition $definition): void
    {
        $output->writeln($prefix . "----> \033[0;33m(root)\033[0m (" . $level . ')');
        $output->writeln($prefix . '      Name: "' . $definition->getName() . '"');
        $output->writeln($prefix . '      Route: "' . ($definition->getRouteName() ?? '__UNSET__') . '"');
        $output->writeln($prefix . '      Expression: "' . $definition->getExpression() . '"');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach ($this->resolver->all() as $node) {
            $this->printNode($node, $output, 1);
            $output->writeln('');
        }

        return Command::SUCCESS;
    }

    private function printNode(BreadcrumbNode $node, OutputInterface $output, int $level): void
    {
        $prefix = str_repeat("\t", $level);
        $definition = $node->getDefinition();
        match (true) {
            $definition instanceof RouteBreadcrumbDefinition => $this->printRouteDefinition($output, $prefix, $definition, $level),
            $definition instanceof RootBreadcrumbDefinition => $this->printRootDefinition($output, $prefix, $level, $definition)
        };
        $output->writeln($prefix . '      Variables: [' . implode(', ', $definition->getVariables()) . ']');
        $child = $node->getParent();
        if (null !== $child) {
            $this->printNode($child, $output, ++$level);
        }
    }
}
