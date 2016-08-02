<?php

namespace Iwanli\Workbench\Console;

use Illuminate\Console\Command;
use Iwanli\Workbench\Packages\Package;
use Iwanli\Workbench\Packages\PackageCreator;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class WorkbenchMakeCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'iworkbench';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new package workbench';

    /**
     * The package creator instance.
     *
     * @var \Illuminate\Workbench\PackageCreator
     */
    protected $creator;

    /**
     * Create a new make workbench command instance.
     *
     * @param \Pingpong\Workbench\PackageCreator $creator
     */
    public function __construct(PackageCreator $creator)
    {
        parent::__construct();

        $this->creator = $creator;
    }

    /**
     * Execute the console command.
     */
    public function fire()
    {
        $workbench = $this->runCreator($this->buildPackage());
        
        $this->info('Package workbench created!');

        $this->callComposerUpdate($workbench);
    }

    /**
     * Run the package creator class for a given Package.
     *
     * @param \Pingpong\Workbench\Package $package
     *
     * @return string
     */
    protected function runCreator($package)
    {
        $config = $this->laravel['config']['workbench'];

        $path = $this->laravel['path.base'].DIRECTORY_SEPARATOR.$config['root_dir'];

        $plain = !$this->option('resources');

        return $this->creator->create($package, $path, $plain);
    }

    /**
     * Call the composer update routine on the path.
     *
     * @param string $path
     */
    protected function callComposerUpdate($path)
    {
        chdir($path);

        passthru('composer install --dev');
    }

    /**
     * Build the package details from user input.
     *
     * @return \Illuminate\Workbench\Package
     *
     * @throws \UnexpectedValueException
     */
    protected function buildPackage()
    {
        list($vendor, $name) = $this->getPackageSegments();

        $config = $this->laravel['config']['workbench'];

        if (is_null($config['email'])) {
            throw new \UnexpectedValueException("Please set the author's email in the workbench configuration file.");
        }

        return new Package($vendor, $name, $config['name'], $config['email']);
    }

    /**
     * Get the package vendor and name segments from the input.
     *
     * @return array
     */
    protected function getPackageSegments()
    {
        $package = $this->argument('package');
        if (count(explode('/', $package, 2)) == 2 ) {
            return array_map('studly_case', explode('/', $package, 2));
        }

        return array_map('studly_case', explode(DIRECTORY_SEPARATOR, $package, 2));
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('package', InputArgument::REQUIRED, 'The name (vendor/name) of the package.'),
        );
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
            array('resources', 'r', InputOption::VALUE_NONE, 'Create Laravel specific directories.'),
        );
    }
}
