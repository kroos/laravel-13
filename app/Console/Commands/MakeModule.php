<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class MakeModule extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	// protected $signature = 'app:make-module';
	protected $signature = 'make:module {name}';


	/**
	 * The console command description.
	 *
	 * @var string
	 */
	// protected $description = 'Command description';
	protected $description = 'Generate a full module (model, controller, requests, policy, resource)';

	/**
	 * Execute the console command.
	 */
	public function handle()
	{
		$name = $this->argument('name');
		$class = class_basename($name);

		$folder = str_replace(
			'\\',
			'/',
			Str::beforeLast($name, '\\')
		);

		$viewPath = resource_path(
			'views/' .
			// Str::lower($folder) .
			// '/' .
			// Str::kebab(Str::plural($class))
			Str::kebab($class)		// TestProcedures => test-procedures
			// Str::camel($class)		// TestProcedures => testProcedures
		);

		$modulePath = resource_path(
			'js/modules/' .
			// Str::lower($folder) .
			// '/' .
			// Str::kebab(Str::plural($class))
			// Str::kebab($class)		// TestProcedures => test-procedures
			Str::camel($class)		// TestProcedures => testProcedures
		);

// dd(
// $viewPath,
// $modulePath
// );

		$this->writeFile(
			app_path("Models/{$folder}/{$class}.php"),
			$this->buildStub('model.stub', [
				'namespace' => "App\\Models\\{$folder}",
				'class' => $class,
			])
		);

		// $this->call('make:model', [
		// 	'name' => $name,
		// 	'-m' => true 						//migration
		// ]);

		$this->writeFile(
			app_path("Http/Controllers/{$folder}/{$class}Controller.php"),
			$this->buildStub('controller.model.stub', [
				'namespace' => "App\\Http\\Controllers\\{$folder}",
				'requestNamespace' => $folder,
				'resourceNamespace' => $folder,
				'modelNamespace' => $folder,
				'class' => $class,
				'modelVariable' => Str::camel($class),
				'viewVariable' => Str::kebab($class),
			])
		);

		// $this->call('make:controller', [
		// 	'name' => "{$name}Controller",
		// 	'--resource' => true,
		// 	'--model' => $name
		// ]);

		$this->writeFile(
			app_path("Http/Requests/{$folder}/Store{$class}Request.php"),
			$this->buildStub('store-request.stub', [
				'namespace' => "App\\Http\\Requests\\{$folder}",
				'namespacemodel' => $folder,
				'class' => $class,
			])
		);

		$this->writeFile(
			app_path("Http/Requests/{$folder}/Update{$class}Request.php"),
			$this->buildStub('update-request.stub', [
				'namespace' => "App\\Http\\Requests\\{$folder}",
				'namespacemodel' => $folder,
				'class' => $class,
			])
		);

		// $this->call('make:request', [
		// 	'name' => "{$this->folder($name)}\\Store{$this->className($name)}Request"
		// ]);

		// $this->call('make:request', [
		// 	'name' => "{$this->folder($name)}\\Update{$this->className($name)}Request"
		// ]);

		// $this->call('make:policy', [
		// 	'name' => "{$this->className($name)}Policy",
		// 	'--model' => $name
		// ]);

		// $this->writeFile(
		// 	app_path("Policies/{$folder}/{$class}Policy.php"),
		// 	$this->buildStub('policy.stub', [
		// 		'namespace' => "App\\Policies\\{$folder}",
		// 		'class' => $class,
		// 		'modelNamespace' => $folder,
		// 		'variable' => Str::camel($class),
		// 	])
		// );

		// $this->call('make:resource', [
		// 	'name' => "{$this->className($name)}Resource"
		// ]);

		// $this->writeFile(
		// 	app_path("Http/Resources/{$folder}/{$class}Resource.php"),
		// 	$this->buildStub('resource.stub', [
		// 		'namespace' => "App\\Http\\Resources\\{$folder}",
		// 		'class' => $class,
		// 	])
		// );

		$this->call('make:migration', [
			'name' => 'create_' .
			Str::snake(Str::pluralStudly($class)) .
			'_table'
		]);

		/* View Files */
		$this->writeFile(
			"{$viewPath}/index.blade.php",
			$this->buildStub('views/index.stub', [
				'class' => $class,
				'modelVariable' => Str::camel($class),
				'viewVariable' => Str::kebab($class),
			])
		);

		$this->writeFile(
			"{$viewPath}/create.blade.php",
			$this->buildStub('views/create.stub', [
				'class' => $class,
				'modelVariable' => Str::camel($class),
				'viewVariable' => Str::kebab($class),
			])
		);

		$this->writeFile(
			"{$viewPath}/edit.blade.php",
			$this->buildStub('views/edit.stub', [
				'class' => $class,
				'variable' => Str::camel($class),
				'modelVariable' => Str::camel($class),
				'viewVariable' => Str::kebab($class),
			])
		);

		$this->writeFile(
			"{$viewPath}/show.blade.php",
			$this->buildStub('views/show.stub', [
				'class' => $class,
				'variable' => Str::camel($class),
			])
		);

		$this->writeFile(
			"{$viewPath}/_form.blade.php",
			$this->buildStub('views/_form.stub', [
				'class' => $class,
			])
		);

		$this->writeFile(
			"{$viewPath}/_js.blade.php",
			$this->buildStub('views/_js.stub', [
				'class' => $class,
				'modelVariable' => Str::camel($class),
				'viewVariable' => Str::kebab($class),
			])
		);

		/* JS Files */
		$this->writeFile(
			"{$modulePath}/index.js",
			$this->buildStub('js/index.stub', [
				'class' => $class,
				'variable' => Str::camel($class),
			])
		);

		$this->writeFile(
			"{$modulePath}/show.js",
			$this->buildStub('js/show.stub', [
				'class' => $class,
				'variable' => Str::camel($class),
			])
		);

		$this->writeFile(
			"{$modulePath}/form.js",
			$this->buildStub('js/form.stub', [
				'class' => $class,
			])
		);

	}

	protected function className($name)
	{
		return class_basename($name);
	}

	protected function folder($name)
	{
		return explode('\\', $name)[0];
	}

	protected function buildStub(string $stub, array $replace): string
	{
		$content = file_get_contents(
			base_path("stubs/module/{$stub}")
		);

		foreach ($replace as $key => $value) {
			$content = str_replace(
				"{{ {$key} }}",
				$value,
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
