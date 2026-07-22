<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class MakeModule extends Command
{
	protected $signature = '
		make:module
		{name}
		{--m|model= : Model class to reference (optional)}
		{--M|new-model : Create a new model class with migration (optional)}
		{--type=resource : Stub profile}
	';

	protected $description = "
Generate a module (controller, model, requests, policies, services, blade and javascript).

# Creates controller + model + migration (all named Test\Test)
php artisan make:module Test\Test -M

# References existing model only, no files created
php artisan make:module Test\Test -m App\Models\User

# Reference existing model + create new one from it
php artisan make:module Test\Test -m App\Models\User -M

# No model option at all (model name = controller name, no files created)
php artisan make:module Test\Test
	";

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

		// core
		$controller = $name;
		$model = $this->option('model') ?: $name;
		$folderController = str_replace(
														'\\',
														'/',
														Str::beforeLast($name, '\\')
												);
		$folderModel = str_replace(
												'\\',
												'/',
												Str::beforeLast($model, '\\')
										);
		$classController = class_basename($name);
		$classModel = class_basename($model);
		$namespaceController = $controller;
		$namespaceModel = $model;
		$fC = ($folderController === $controller)?null:'\\'.$folderController;
		$fM = ($folderModel === $model)?null:'\\'.$folderModel;
		$variableController = Str::kebab($classController);
		$variableModel = Str::camel($classModel);

		// dd(
		// 	'controller = '.$controller,
		// 	'model = '.$model,
		// 	'classController = '.$classController,
		// 	'classModel = '.$classModel,
		// 	'namespaceController = '.$namespaceController,
		// 	'namespaceModel = '.$namespaceModel,
		// 	'folderController = '.$fC,
		// 	'folderModel = '.$fM,
		// 	'variableController = '.$variableController,
		// 	'variableModel = '.$variableModel,
		// );

		$replace = [
			'controller' => $controller,
			'model' => $model,
			'classController' => $classController,
			'classModel' => $classModel,
			'namespaceController' => $namespaceController,
			'namespaceModel' => $namespaceModel,
			'folderController' => $fC,
			'folderModel' => $fM,
			'variableController' => $variableController,
			'variableModel' => $variableModel,
		];

		$viewPath = resource_path(
				'views/' . $variableController
		);

		$jsPath = resource_path(
			'js/modules/' . $variableController
		);

		$profile = $this->option('type');

		/*
		|--------------------------------------------------------------------------
		| Controller
		|--------------------------------------------------------------------------
		*/

		$this->writeFile(
			app_path("Http/Controllers/{$namespaceController}Controller.php"),
			$this->buildStub('controller.stub', $replace, $profile)
		);

		/*
		|--------------------------------------------------------------------------
		| Requests
		|--------------------------------------------------------------------------
		*/

		$this->writeFile(
			app_path("Http/Requests/{$fC}/Store{$classController}Request.php"),
			$this->buildStub('store-request.stub', $replace, $profile)
		);

		$this->writeFile(
			app_path("Http/Requests/{$fC}/Update{$classController}Request.php"),
			$this->buildStub('update-request.stub', $replace, $profile)
		);

		/*
		|--------------------------------------------------------------------------
		| Policy
		|--------------------------------------------------------------------------
		*/

		$this->writeFile(
			app_path("Policies/{$namespaceController}Policy.php"),
			$this->buildStub('policy.stub', $replace, $profile)
		);

		/*
		|--------------------------------------------------------------------------
		| Resources
		|--------------------------------------------------------------------------
		*/

		// $this->writeFile(
		// 	app_path("Resources/{$namespaceController}Resource.php"),
		// 	$this->buildStub('resource.stub', $replace, $profile)
		// );

		/*
		|--------------------------------------------------------------------------
		| Services
		|--------------------------------------------------------------------------
		*/

		$this->writeFile(
			app_path("Services/{$namespaceController}Service.php"),
			$this->buildStub('service.stub', $replace, $profile)
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
				"{$jsPath}/{$js}.js",
				$this->buildStub("js/{$js}.stub", $replace, $profile)
			);
		}

		/*
		|--------------------------------------------------------------------------
		| New Model + Migration
		|--------------------------------------------------------------------------
		|
		| When --new-model (-M) is used, generate the model file and its
		| migration. The model is also used as the reference model in
		| stubs unless --model (-m) is explicitly provided.
		|
		*/

		$createModel = $this->option('new-model');

		if ($createModel) {
			// Generate model file
			$this->writeFile(
				app_path("Models/{$namespaceModel}.php"),
				$this->buildStub('model.stub', $replace, $profile)
			);

			// Generate migration
			$this->call('make:migration', [
				'name' => 'create_'
					. Str::snake(Str::pluralStudly($classModel))
					. '_table',
			]);

			$this->info("Model [{$namespaceModel}] created with migration.");
		}

		/*
		|--------------------------------------------------------------------------
		| Routes
		|--------------------------------------------------------------------------
		|
		| Auto-append route entries into routes/auth.php
		|
		*/

		$this->appendRoutesToAuth(
			$namespaceController,
			$classController,
			$variableController,
			$variableModel
		);

		$this->info('Resource generated successfully.');

		return self::SUCCESS;
	}








	protected function className($name)
	{
		return class_basename($name);
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

	protected function appendRoutesToAuth(
		string $namespaceController,
		string $classController,
		string $variableController,
		string $variableModel
	): void {

		$path = base_path('routes/auth.php');
		$content = file_get_contents($path);

		$controllerMarker = '// all controller here';
		$routeMarker = '// insert your normal page route here';

		$routes = PHP_EOL;
		$routes .= "\tRoute::resources([" . PHP_EOL;
		$routes .= "\t\t'{$variableController}' => {$classController}Controller::class," . PHP_EOL;
		$routes .= "\t]);" . PHP_EOL;
		$routes .= PHP_EOL;
		$routes .= "// \tRoute::controller({$classController}Controller::class)->group(function(){" . PHP_EOL;
		$routes .= "// \t\tRoute::prefix('{$variableController}')->name('{$variableController}.')->group(function(){" . PHP_EOL;
		$routes .= "// \t\t\tRoute::get('/', 'index')->name('index');" . PHP_EOL;
		$routes .= "// \t\t\tRoute::get('/', 'create')->name('create');" . PHP_EOL;
		$routes .= "// \t\t\tRoute::get('/', 'store')->name('store');" . PHP_EOL;
		$routes .= "// \t\t\tRoute::get('/{{$variableController}}', 'show')->name('show');" . PHP_EOL;
		$routes .= "// \t\t\tRoute::get('/{{$variableController}}/edit', 'edit')->name('edit');" . PHP_EOL;
		$routes .= "// \t\t\tRoute::patch('/{{$variableController}}', 'update')->name('update');" . PHP_EOL;
		$routes .= "// \t\t\tRoute::delete('/{{$variableController}}', 'destroy')->name('destroy');" . PHP_EOL;
		$routes .= "// \t\t});" . PHP_EOL;
		$routes .= "// \t});" . PHP_EOL;
		$routes .= PHP_EOL;

		if (! str_contains($content, $routeMarker)) {
			$this->warn("Marker [{$routeMarker}] not found in routes/auth.php. Skipping route append.");
			return;
		}
		if (! str_contains($content, $controllerMarker)) {
			$this->warn("Marker [{$controllerMarker}] not found in routes/auth.php. Skipping route append.");
			return;
		}

		$content = str_replace($controllerMarker, $controllerMarker . PHP_EOL . "\t{$namespaceController}Controller," . PHP_EOL, $content);
		$content = str_replace($routeMarker, $routeMarker . $routes, $content);

		file_put_contents($path, $content);

		$this->info('Routes appended to [routes/auth.php].');
	}
}
