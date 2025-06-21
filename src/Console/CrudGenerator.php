<?php

namespace TuVendor\CrudGenerator\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CrudGenerator extends Command
{
    protected $signature = 'crud:generator {--create=mcrfi} {model}';
    protected $description = 'Genera modelo, controlador, request, test, factory y migration para un modelo';

    public function handle(): void
    {
        $functions = $this->option('create');
        $modelName = $this->argument('model');
        $lista = str_split($functions);

        $model = in_array('m', $lista);
        $controller = in_array('c', $lista);
        $request = in_array('r', $lista);
        $test = in_array('t', $lista);
        $factory = in_array('f', $lista);
        $migration = in_array('i', $lista);

        if ($model) {
            $this->comment('Generando modelo...');
            $this->generateElem($modelName, 'Model');
            $this->comment('Modelo generado');
        }
        if ($controller) {
            $this->comment('Generando controlador...');
            $this->generateElem($modelName, 'Controller');
            $this->comment('Controlador generado');
        }
        if ($request) {
            $this->comment('Generando request...');
            $this->generateElem($modelName, 'Request');
            $this->comment('Request generado');
        }
        if ($test) {
            $this->comment('Generando pruebas...');
            $this->generateElem($modelName, 'Test');
            $this->comment('Pruebas generadas');
        }
        if ($factory) {
            $this->comment('Generando factory...');
            $this->generateElem($modelName, 'Factory');
            $this->comment('Factory generada');
        }
        if ($migration) {
            $this->comment('Generando migraciones...');
            $name = strtolower(Str::snake(Str::plural($modelName)));
            Artisan::call('make:migration create_' . $name . "_table --create=$name");
            $this->comment('Migración generada');
            $this->createResourcePath($modelName);
            $this->comment('Ruta API agregada');
        }
    }

    public function getStubs(string $type): string
    {

        return file_get_contents(__DIR__ . '/../../../stubs/' . $type . '.stub');
    }

    public function generateElem(string $name, string $type): bool
    {
        $model_lower = Str::lower(trim($name));
        $model_plural = Str::plural($model_lower);
        $model_name = $name;
        $controller_name = $model_name . 'Controller';
        $request_name = $model_name . 'Request';
        $factory_name = $model_name . 'Factory';
        $test_name = $model_name . 'Test';
        $snake_name = Str::lower(Str::snake($name, '-'));
        $snake_under = Str::lower(Str::snake($name));

        $template = str_replace(
            [
                '{{ModelName}}',
                '{{model_lower}}',
                '{{model_plural}}',
                '{{ControllerName}}',
                '{{RequestName}}',
                '{{TestName}}',
                '{{snake_name}}',
                '{{snake_under}}',
            ],
            [
                $model_name,
                $model_lower,
                $model_plural,
                $controller_name,
                $request_name,
                $test_name,
                $snake_name,
                $snake_under,
            ],
            $this->getStubs($type)
        );

        switch ($type) {
            case 'Model':
                $path = app_path("Models/{$model_name}.php");
                break;
            case 'Controller':
                $path = app_path("Http/Controllers/{$controller_name}.php");
                break;
            case 'Request':
                $path = app_path("Http/Requests/{$request_name}.php");
                break;
            case 'Factory':
                $path = database_path("factories/{$factory_name}.php");
                break;
            case 'Test':
                $path = base_path("tests/Feature/{$test_name}.php");
                break;
            default:
                return false;
        }

        if (!is_file($path)) {
            file_put_contents($path, $template);
        }

        return true;
    }

    private function createResourcePath(string $name): void
    {
        $snake = Str::lower(Str::snake($name, '-'));
        $path_to_file = base_path('routes/api.php');
        $append_route = "Route::apiResource('$snake', " . $name . "Controller::class);\n";
        File::append($path_to_file, $append_route);
    }
}