@extends('errors.layout')

@section('content')
<div class="col-sm-12 d-flex flex-column justify-content-center align-items-center">
	<div class="card">
		<div class="card-header d-flex justify-content-between">
			<h3 class="my-auto">Error </h3>
			<a href="{{ url('/dashboard') }}" class="my-auto btn btn-sm btn-outline-green">
				<i class="fa-regular fa-house fa-beat"></i> Home
			</a>
		</div>
		<div class="card-body">
			<h1 class="text-center">Error 419 : Session Expired</h1>
			<a href="{{ url('/dashboard') }}" class="">
				<img src="{{ asset('images/errors/419-error.jpg') }}" class="img-fluid rounded " alt="">
			</a>
		</div>
		<div class="card-footer d-flex justify-content-end">
			<a href="{{ url('/dashboard') }}" class="my-auto btn btn-sm btn-outline-green me-1">
				<i class="fa-regular fa-house fa-beat"></i> Home
			</a>
		</div>
	</div>
</div>

@endsection
