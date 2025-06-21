<?php

namespace TuVendor\CrudGenerator;

use Illuminate\Support\ServiceProvider;
use TuVendor\CrudGenerator\Console\CrudGenerator;

class CrudGeneratorServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->commands([
            CrudGenerator::class
        ]);
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../stubs' => resource_path('stubs'),
        ], 'crud-generator-stubs');
    }
}
