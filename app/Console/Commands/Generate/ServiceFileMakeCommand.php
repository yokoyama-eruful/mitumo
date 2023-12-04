<?php

namespace App\Console\Commands\Generate;

use Illuminate\Console\GeneratorCommand as Command;

class ServiceFileMakeCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:service {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Service class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Service';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/stubs/service.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $root_namespace
     *
     * @return string
     */
    protected function getDefaultNamespace($root_namespace)
    {
        $full_namespace = $root_namespace;

        return $full_namespace . '\Services';
    }
}
