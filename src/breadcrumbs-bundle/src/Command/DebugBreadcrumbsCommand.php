<?php

namespace R1n0x\BreadcrumbsBundle\Command;

use R1n0x\BreadcrumbsBundle\Internal\Model\BreadcrumbNode;
use R1n0x\BreadcrumbsBundle\Internal\Resolver\NodesResolver;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
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
        $output->writeln($prefix . "----> \033[0;32m" . $definition->getRouteName() . "\033[0m (" . $level . ')');
        $output->writeln($prefix . '      Expression: "' . $definition->getExpression() . '"');
        $output->writeln($prefix . '      Variables: [' . implode(', ', $definition->getVariables()) . ']');
        $output->writeln($prefix . '      Parameters: [' . implode(', ', $definition->getParameters()) . ']');
        $child = $node->getParent();
        if ($child) {
            $this->printNode($child, $output, ++$level);
        }
    }
}
