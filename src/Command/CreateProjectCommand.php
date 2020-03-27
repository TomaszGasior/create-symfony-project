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
use Symfony\Component\Console\Style\SymfonyStyle;

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
            ->setAliases(['symfony-new', 'new-symfony', 'sf-new'])
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
                $opt('unstable', 'Use next in-development Symfony release, not for production'),
                $opt('minimal', 'Use plain symfony/skeleton (default)'),
                $opt('full', 'Use full-stack symfony/website-skeleton'),
                $opt('website', 'Use full-stack symfony/website-skeleton'),
                $opt('demo', 'Use latest demo application from symfony/demo'),
            ])
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $version = $this->getVersion($input);
        $package = $this->getPackage($input);
        $packageVersion = $this->getPackageVersion($input, $version);
        $directory = $input->getArgument('directory');

        $io = new SymfonyStyle($input, $output);
        $application = $this->getApplication();

        $innerCommand = $application->find('create-project');
        $innerInput = new ArrayInput([
            'command' => 'create-project',
            'package' => $package,
            'directory' => $directory,
        ] + ($packageVersion ? ['version' => $packageVersion] : []));

        $output->writeln(sprintf('Running <options=bold>composer %s</>', $innerInput));

        $interactive = $input->isInteractive();
        $verbosity = $output->getVerbosity();

        if (false === $output->isVerbose()) {
            $input->setInteractive(false);
            $innerInput->setInteractive(false);
            $output->setVerbosity(OutputInterface::VERBOSITY_QUIET);
        }
        $innerCommand->run($innerInput, $output);

        $input->setInteractive($interactive);
        $output->setVerbosity($verbosity);

        $io->success(
            sprintf(
                'Symfony %s is ready to use in "%s" directory.',
                (Skeleton::DEMO === $package) ? 'demo application' : $version,
                $directory,
            )
        );

        return 0;
    }

    private function getPackage(InputInterface $input): string
    {
        switch (true) {
            case $input->getOption('full'):
            case $input->getOption('website'):
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
        switch (true) {
            case $input->getOption('demo'):
                return null;

            case $input->getOption('current'):
            case $input->getOption('stable'):
                return $this->versionApi->getVersionOfRelease(Release::STABLE);

            case $input->getOption('next'):
            case $input->getOption('unstable'):
                return $this->versionApi->getVersionOfRelease(Release::NEXT);

            case $input->getOption('lts'):
            default:
                return $this->versionApi->getVersionOfRelease(Release::LTS);
        }
    }

    private function getPackageVersion(InputInterface $input, ?string $version): ?string
    {
        $format = function(string $version): string {
            return preg_filter('/^([0-9]+\.[0-9]+)(.*)/', '$1.*', $version);
        };

        switch (true) {
            case $input->getOption('demo'):
                return null;

            case $input->getOption('next'):
            case $input->getOption('unstable'):
                return $format($version) . '@dev';

            default:
                return $format($version);
        }
    }
}
