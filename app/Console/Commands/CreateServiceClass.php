<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Pluralizer;
use Illuminate\Filesystem\Filesystem;

class CreateServiceClass extends FileFactoryCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {classname}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this command creates a new service pattern';

    /**
     * Execute the console command.
     */

    function setStubName(): string
    {
        return "servicepattern";
    }
    function setStubPath(): string
    {
        return "App\\Services\\";
    }
    function setSuffix(): string
    {
        return "Service";
    }
}
