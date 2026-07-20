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

	protected $description = 'Generate a module (controller, requests, policies, services, blade and javascript)';

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
		| Models
		|--------------------------------------------------------------------------
		*/

		// $this->writeFile(
		// 	app_path("Models/{$namespaceModel}.php"),
		// 	$this->buildStub('model.stub', $replace, $profile)
		// );

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

		$this->info('Resource generated successfully.');
		$this->info('Route should be like this.');
		$this->info("
use App\Http\Controllers\{$namespaceController}Controller;
Route::resources([
	'{$variableController}' => {$namespaceController}Controller::class,
]);
Or
Route::controller({$namespaceController}Controller::class)->group(function(){
	Route::prefix('{$variableController}')->name('{$variableController}.')->group(function(){
		Route::get('/', 'index')->name('index');
		Route::get('/', 'create')->name('create');
		Route::get('/', 'store')->name('store');
		Route::get('/{{$variableModel}}', 'show')->name('show');
		Route::get('/{{$variableModel}}/edit', 'edit')->name('edit');
		Route::patch('/{{$variableModel}}', 'update')->name('update');
		Route::delete('/{{$variableModel}}', 'destroy')->name('destroy');
	});
});
		");

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
}
