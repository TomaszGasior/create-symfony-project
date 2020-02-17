<?php

namespace Mushrooms\CreateSymfonyProject\Composer;

use Composer\Plugin\Capability\CommandProvider as CommandProviderCapability;
use Mushrooms\CreateSymfonyProject\Command\CreateProjectCommand;

class CommandProvider implements CommandProviderCapability
{
    public function getCommands(): array
    {
        return [new CreateProjectCommand];
    }
}
