<?php
use \Carbon\Carbon;
$currentYear = Carbon::now()->year;
?>

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="keywords" content="" />
	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>{{ config('app.name', 'Laravel') }}</title>

	<link href="{{ asset('storage/web/icon2.png') }}" type="image/x-icon" rel="icon" />

	<!-- Styles / Scripts -->
	@vite(['resources/scss/app.scss', 'resources/css/app.css'])

	<!-- Bootswatch Cerulean CSS -->
	<link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
	<!-- Livewire CSS -->

</head>
<body class="bg-primary bg-opacity-25 min-vh-100 d-flex flex-column">

	<div class="container-fluid flex-fill d-flex flex-column">

		<div class="col-sm-12 mx-auto my-2">
		</div>


		<div class="container-fluid p-1 mx-auto d-flex justify-content-between flex-fill">

			<div class="col-sm-1 m-0 align-self-stretch">
			</div>


			<div class="col-sm-10 m-0 my-2 p-1 align-self-center">
				@yield('content')
			</div>

			<div class="col-sm-1 m-0 p-1 align-self-stretch">
			</div>

		</div>

	</div>
	<!-- footer -->
	<div class="container m-0 mx-auto py-1 align-self-end text-center text-sm text-light-emphasis">
		&copy; {{ config('app.name', 'Laravel') }} {{ $currentYear }}
	</div>
</body>
@vite(['resources/js/app.js'])
</html>

