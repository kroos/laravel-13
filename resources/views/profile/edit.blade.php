@extends('layouts.app')

@section('content')
<div class="col-sm-12 row d-flex align-items-center justify-content-center">

	<form method="post" action="{{ route('password.update') }}">
		@csrf
		@method('PUT')
		<div class="card my-2">
			<div class="card-header d-flex justify-content-between">
				<h3 class="my-auto">Update Password</h3>
			</div>
			<div class="card-body">
				@include('profile.partials.update-password-form')
			</div>
			<div class="card-footer d-flex justify-content-end">
				<button type="submit" class="my-auto btn btn-sm btn-outline-primary me-1">
					<i class="fa-regular fa-floppy-disk"></i> Submit
				</button>
				<a href="{{-- route('route.index') --}}" class="my-auto btn btn-sm btn-outline-secondary me-1">
					<i class="fa-solid fa-delete-left"></i> Cancel
				</a>
			</div>
		</div>
	</form>


	<form method="post" action="{{ route('profile.update') }}" >
		@csrf
		@method('patch')

		<div class="card my-2">
			<div class="card-header d-flex justify-content-between">
				<h3 class="my-auto">Profile Information</h3>
			</div>
			<div class="card-body">
				@include('profile.partials.update-profile-information-form')
			</div>
			<div class="card-footer d-flex justify-content-end">
				<button type="submit" class="my-auto btn btn-sm btn-outline-primary me-1">
					<i class="fa-regular fa-floppy-disk"></i> Submit
				</button>
				<a href="{{-- route('route.index') --}}" class="my-auto btn btn-sm btn-outline-secondary me-1">
					<i class="fa-solid fa-delete-left"></i> Cancel
				</a>
			</div>
		</div>

	</form>


	<div class="card my-2">
		<div class="card-header d-flex justify-content-between">
			<h3 class="my-auto">Delete Account</h3>
		</div>
		<div class="card-body">
			@include('profile.partials.delete-user-form')
		</div>
	</div>


</div>
@endsection

@section('js')
@endsection
