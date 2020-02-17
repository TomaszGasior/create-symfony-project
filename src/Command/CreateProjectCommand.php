<?php

namespace Mushrooms\CreateSymfonyProject\Command;

use Composer\Command\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateProjectCommand extends BaseCommand
{
    protected function configure(): void
    {
        $this->setName('create-symfony-project');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return 0;
    }
}
