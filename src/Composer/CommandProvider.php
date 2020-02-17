<?php

namespace Mushrooms\CreateSymfonyProject\Composer;

use Composer\Plugin\Capability\CommandProvider as CommandProviderCapability;
use Mushrooms\CreateSymfonyProject\Command\CreateProjectCommand;
use Mushrooms\CreateSymfonyProject\Symfony\VersionApi;
use Symfony\Component\HttpClient\HttpClient;

class CommandProvider implements CommandProviderCapability
{
    public function getCommands(): array
    {
        $versionApi = new VersionApi(HttpClient::create());
        $command = new CreateProjectCommand($versionApi);

        return [$command];
    }
}
