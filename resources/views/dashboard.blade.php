@extends('layouts.app')

@section('content')
<div class="col-sm-12 d-flex flex-column align-items-center justify-content-center">
<?php
// if (request()->session()->missing('users')) {
// 	request()->session()->put('users', \Auth::user());
// }
// dd(request()->session()->all(), \Auth::user())
// request()->session()->flush();
?>
	<h3>Dashboard</h3>
	<p class="text-gray text-center">You're logged in!</p>

	<div class="card col-sm-6">
		<div class="card-header">Test API</div>
		<div class="card-body">
			<form action="" method="GET" id="form" class="" enctype="multipart/form-data">
				@csrf

				<div class="row col-sm-12 @error('option') has-error @enderror">
					<label for="opt" class="col-form-label col-sm-6">Select 2:</label>
					<div class="col-sm-6 my-auto">
						<select name="option" id="opt" value="{{ old('option')}}" class="form-select form-select-sm @error('option') is-invalid @enderror" placeholder="Please choose"></select>
					</div>
					@error('option')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>


				<div class="mt-3">
					<button type="submit" class="btn btn-sm btn-success">Save</button>
				</div>
			</form>
		</div>
	</div>


</div>
@endsection

@section('js')
	window.data = {
		routes: {
			getYesNoOptions: "{{ route('getYesNoOptions') }}",
		},
		old: {
			option: @json(old('option', @$variable?->hasmanyModel?->get()?->toArray() ?? [])),
		},
	};
@endsection
