<?php

namespace Mushrooms\CreateSymfonyProject\Command;

use Composer\Command\BaseCommand;
use Mushrooms\CreateSymfonyProject\Symfony\Release;
use Mushrooms\CreateSymfonyProject\Symfony\Skeleton;
use Mushrooms\CreateSymfonyProject\Symfony\VersionApi;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CreateProjectCommand extends BaseCommand
{
    private $versionApi;

    public function __construct(VersionApi $versionApi)
    {
        $this->versionApi = $versionApi;

        parent::__construct();
    }

    protected function configure(): void
    {
        $opt = function(string $name, string $description): InputOption {
            return new InputOption($name, null, InputOption::VALUE_NONE, $description);
        };

        $this
            ->setName('create-symfony-project')
            ->setDescription('Creates new Symfony project from official skeleton.')
            ->setDefinition([
                new InputArgument(
                    'directory',
                    InputArgument::REQUIRED,
                    'Directory where the files should be created'
                ),
                $opt('lts', 'Use latest long-term support Symfony release (default)'),
                $opt('stable', 'Use latest non-LTS stable Symfony release'),
                $opt('current', 'Use latest non-LTS stable Symfony release'),
                $opt('next', 'Use next in-development Symfony release, not for production'),
                $opt('minimal', 'Use plain symfony/skeleton (default)'),
                $opt('full', 'Use full-stack symfony/website-skeleton'),
                $opt('demo', 'Use latest demo application from symfony/demo'),
            ])
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $innerInput = new ArrayInput([
            'command' => 'create-project',
            'package' => $this->getPackage($input),
            'directory' => $input->getArgument('directory'),
            'version' => $this->getVersion($input),
        ]);

        return $this->getApplication()->run($innerInput, $output);
    }

    private function getPackage(InputInterface $input): string
    {
        switch (true) {
            case $input->getOption('full'):
                return Skeleton::FULL;

            case $input->getOption('demo'):
                return Skeleton::DEMO;

            case $input->getOption('minimal'):
            default:
                return Skeleton::MINIMAL;
        }
    }

    private function getVersion(InputInterface $input): ?string
    {
        if ($input->getOption('demo')) {
            return null;
        }

        $format = function(string $version): string {
            return preg_filter('/^([0-9]+\.[0-9]+)(.*)/', '$1.*', $version);
        };

        switch (true) {
            case $input->getOption('current'):
            case $input->getOption('stable'):
                return $format($this->versionApi->getVersionOfRelease(Release::STABLE));

            case $input->getOption('next'):
                return $format($this->versionApi->getVersionOfRelease(Release::NEXT)) . '@dev';

            case $input->getOption('lts'):
            default:
                return $format($this->versionApi->getVersionOfRelease(Release::LTS));
        }
    }
}
