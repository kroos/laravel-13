<?php
use App\Providers\AppServiceProvider;

return [
	AppServiceProvider::class,

		// Custom Providers
	App\Extensions\Helper\HelperServiceProvider::class,
];
