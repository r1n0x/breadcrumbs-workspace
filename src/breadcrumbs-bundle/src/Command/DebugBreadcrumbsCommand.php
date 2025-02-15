<?php

namespace R1n0x\BreadcrumbsBundle\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
#[AsCommand(
    name: 'debug:breadcrumbs',
    description: "Display breadcrumbs tree for an application"
)]
class DebugBreadcrumbsCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return Command::SUCCESS;
    }
}