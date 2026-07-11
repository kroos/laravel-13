<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class MakeControl extends Command
{
	protected $signature = '
		make:control
		{name}
		{--m|model= : Model class (optional)}
		{--type=resource : Stub profile}
	';

	protected $description = 'Generate a full module (controller, requests)';

	public function handle()
	{
		return match ($this->option('type')) {
			'resource' => $this->generateResource(),
			default => $this->fail("Unknown profile [{$this->option('type')}]"),
		};
	}

	protected function generateResource()
	{
		$name = $this->argument('name');

		/*
		|--------------------------------------------------------------------------
		| Auto detect model
		|--------------------------------------------------------------------------
		|
		| php artisan make:control ControlTest\ControlGerabah
		|
		| becomes
		|
		| App\Models\ControlTest\ControlGerabah
		|
		*/

		$model = $this->option('model') ?: $name;
		$class = class_basename($name);
		$folder = str_replace(
				'\\',
				'/',
				Str::beforeLast($name, '\\')
		);
		$modelClass = class_basename($model);
		$modelNamespace = Str::beforeLast($model, '\\');
		if ($modelNamespace === $model) {
				$modelNamespace = '';
		}
		$replace = [
			'namespace' => "App\\Http\\Controllers\\{$folder}",
			'requestNamespace' => $folder,
			'resourceNamespace' => $folder,
			'namespacemodel' => $folder,
			'controllerClass' => $class,
			'class' => $class,
			'variable' => Str::camel($class),
			'modelVariable' => Str::camel($modelClass),
			'viewVariable' => Str::kebab($class),
			'modelNamespace' => $modelNamespace,
			'modelClass' => $modelClass,
		];

		$viewPath = resource_path(
				'views/' . Str::kebab($class)
		);

		$modulePath = resource_path(
			'js/modules/' . Str::camel($class)
		);

		$profile = $this->option('type');

		/*
		|--------------------------------------------------------------------------
		| Controller
		|--------------------------------------------------------------------------
		*/

		$this->writeFile(
			app_path("Http/Controllers/{$folder}/{$class}Controller.php"),
			$this->buildStub('controller.stub', $replace, $profile)
		);

		/*
		|--------------------------------------------------------------------------
		| Requests
		|--------------------------------------------------------------------------
		*/

		$this->writeFile(
			app_path("Http/Requests/{$folder}/Store{$class}Request.php"),
			$this->buildStub('store-request.stub', $replace, $profile)
		);

		$this->writeFile(
			app_path("Http/Requests/{$folder}/Update{$class}Request.php"),
			$this->buildStub('update-request.stub', $replace, $profile)
		);

		/*
		|--------------------------------------------------------------------------
		| Views
		|--------------------------------------------------------------------------
		*/

		foreach ([
			'index',
			'create',
			'edit',
			'show',
			'_form',
			'_js',
		] as $view) {
			$this->writeFile(
				"{$viewPath}/{$view}.blade.php",
				$this->buildStub("views/{$view}.stub", $replace, $profile)
			);
		}

		/*
		|--------------------------------------------------------------------------
		| JS
		|--------------------------------------------------------------------------
		*/

		foreach ([
			'index',
			'show',
			'form',
		] as $js) {
			$this->writeFile(
				"{$modulePath}/{$js}.js",
				$this->buildStub("js/{$js}.stub", $replace, $profile)
			);
		}

		$this->info('Resource generated successfully.');

		return self::SUCCESS;
	}








	protected function className($name)
	{
		return class_basename($name);
	}

	protected function folder($name)
	{
		return explode('\\', $name)[0];
	}

	protected function buildStub(
		string $stub,
		array $replace,
		string $profile = 'resource'
	): string {

		$content = file_get_contents(
			base_path("stubs/module/{$profile}/{$stub}")
		);

		foreach ($replace as $key => $value) {
			$content = str_replace(
				"{{ {$key} }}",
				$value ?? '',
				$content
			);
		}
		return $content;
	}

	protected function writeFile(
		string $path,
		string $content
	): void {

		if (! is_dir(dirname($path))) {
			mkdir(dirname($path), 0755, true);
		}

		file_put_contents($path, $content);
	}
}
