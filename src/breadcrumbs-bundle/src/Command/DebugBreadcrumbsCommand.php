<?php

namespace R1n0x\BreadcrumbsBundle\Command;

use R1n0x\BreadcrumbsBundle\Dao\BreadcrumbDao;
use R1n0x\BreadcrumbsBundle\Storage\BreadcrumbsStorage;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
#[AsCommand(
    name: 'debug:breadcrumbs',
    description: "Display breadcrumbs tree for an application"
)]
class DebugBreadcrumbsCommand extends Command
{
    // i know it's written pretty badly, but it's only for debugging purposes.

    private int $errors = 0;

    public function __construct(
        private RouterInterface    $router,
        private BreadcrumbsStorage $storage
    )
    {
        parent::__construct();

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('');
        foreach ($this->storage->all() as $breadcrumb) {
            $this->buildTree($output, $breadcrumb, 1);
        }
        $output->writeln('');

        if ($this->errors > 0) {
            $this->error($output, "Found " . $this->errors . " errors in all the breadcrumb trees.");
            return Command::FAILURE;
        }
        return Command::SUCCESS;
    }

    private function buildTree(OutputInterface $output, BreadcrumbDao $breadcrumb, int $level): void
    {
        $output->writeln($this->getLevelSpacer($level) . $breadcrumb->getRoute() . " \"" . $breadcrumb->getExpression() . "\"");
        $this->printStatusMessages($output, $level, $breadcrumb->getRoute());
        if ($breadcrumb->getParentRoute()) {
            $parentBreadcrumb = $this->storage->get($breadcrumb->getParentRoute());
            if ($parentBreadcrumb) {
                $this->buildTree($output, $parentBreadcrumb, ++$level);
            } else {
                $level++;
                $output->writeln($this->getLevelSpacer($level) . $breadcrumb->getParentRoute());
                $this->printStatusMessages($output, $level, $breadcrumb->getParentRoute());
            }
        }
    }

    private function isValidRoute(string $routeName): bool
    {
        return $this->router->getRouteCollection()->get($routeName) !== null;
    }

    private function getLevelSpacer(int $level, bool $showArrow = true): string
    {
        return str_repeat("   ", $level) . ($level > 1 && $showArrow ? '↳ ' : '');
    }

    private function printStatusMessages(OutputInterface $output, int $level, string $routeName): void
    {
        $prefix = $this->getLevelSpacer($level, false) . '   ';
        if ($this->isValidRoute($routeName)) {
            $this->success($output, $prefix . "Route was found (OK)");
        } else {
            $this->error($output, $prefix . "Route named \"" . $routeName . "\" was not found (ERROR)");
            $this->errors++;
        }
    }

    private function success(OutputInterface $output, string $value): void
    {
        $output->writeln("\033[0;32m" . $value . "\033[0m");
    }

    private function error(OutputInterface $output, string $value): void
    {
        $output->writeln("\033[0;31m" . $value . "\033[0m");
    }
}