@extends('layouts.app')

@section('content')
<div class="col-sm-12 d-flex flex-clumn justify-content-center align-items-center">

	<div class="card">
		<div class="card-header d-flex justify-content-between">
			<h3 class="my-auto">Welcome</h3>
		</div>
		<div class="card-body">




	<form method="POST" action="{{ route('welcome') }}" accept-charset="UTF-8" id="form" autocomplete="off" class="needs-validation" enctype="multipart/form-data">
		@csrf
		<div class="col-sm-12 row justify-content-center align-items-center my-2 m-0 border border-success">
			<h1 class="text-center animate__animated animate__bounce">An animated element</h1>
		</div>

		<div class="col-sm-12 row text-center align-items-center my-2 m-0 border border-success">
			<div class="tw">
				<p class="text-3xl font-bold underline">Hello tailwindcss</p>
				<div class="bg-green-500 text-white px-4 py-2 rounded mb-2">
					Tailwind v3 + Laravel Mix works 🎉
				</div>
				<button type="button" class="btn btn-sm btn-primary">Bootstrap Button</button>
			</div>
			<p>If you want to use tailwindcss class, please wrap it with "tw" class. This has been made to resolve conflicts between bootstrap and tailwindcss</p>
			<p>{{ __('<div class="tw"><p class="text-3xl font-bold underline">Hello tailwindcss</p></div>') }}</p>
			<div class="form-check form-switch my-2">
				<label class="form-check-label" for="switch">
					<input type="checkbox" name="switch" id="switch" class="form-check-input" role="switch">
					@error('switch')
						<div class="invalid-feedback">
							{{ $message }}
						</div>
					@enderror
				Switch checkbox input (to make sure it doesnt conflict with tailwind)</label>
			</div>
		</div>

		<div class="col-sm-12 row justify-content-center align-items-center my-1 m-0 border border-success">
			<p class="">Placeholder text to demonstrate some <a href="#" data-toggle="tooltip" data-bs-title="Default tooltip">inline links</a> with tooltips. This is now just filler, no killer. Content placed here just to mimic the presence of <a href="#" data-toggle="tooltip" data-bs-title="Another tooltip">real text</a>. And all that just to give you an idea of how tooltips would look when used in real-world situations. So hopefully you've now seen how <a href="#" data-toggle="tooltip" data-bs-title="Another one here too">these tooltips on links</a> can work in practice, once you use them on <a href="#" data-toggle="tooltip" data-bs-title="The last tip!">your own</a> site or project.
			</p>
		</div>

		<div class="col-sm-12 row justify-content-center align-items-center my-1 m-0 border border-success">
			<div class="form-group row col-sm-6 border border-primary m-1 @error('name') has-error @enderror">
				<label for="minicolor" class="col-form-label col-sm-4">MiniColors : </label>
				<div class="col-sm-6 my-auto">
					<input type="text" name="minicolor" value="{{ old('minicolor', @$variable->name) }}" id="minicolor" class="form-control form-control-sm @error('minicolor') is-invalid @enderror" placeholder="MiniColors">
					@error('minicolor')
					<div class="invalid-feedback">
						{{ $message }}
					</div>
					@enderror
				</div>
			</div>
		</div>

		<div class="col-sm-12 row justify-content-center align-items-center my-2 m-0 border border-success">
			<div class="form-group row col-sm-6 border border-primary @error('select2') has-error @enderror">
				<label for="select2" class="col-form-label col-sm-4">Select 2:</label>
				<div class="col-sm-8 my-auto">
					<select name="select2" id="select2" class="form-select form-select-sm @error('select2') is-invalid @enderror" placeholder="Please choose">
						<option value="">Please choose</option>
						<option value="1">Pick 1</option>
						<option value="2">Pick 2</option>
					</select>
				</div>
			</div>
		</div>

		<div class="col-sm-12 row justify-content-center align-items-center my-2 m-0 border border-success">
			<div class="form-group row col-sm-6 border border-primary">
				<label for="dp" class="col-form-label col-sm-4">jQuery-ui Datepicker:</label>
				<div class="col-sm-8 my-auto">
					<input type="text" name="datepicker" id="dp" class="form-control form-control-sm @error('datepicker') is-invalid @enderror">
					@error('datepicker')
						<div class="invalid-feedback">
							{{ $message }}
						</div>
					@enderror
				</div>
			</div>
			<figure class="text-start">
				<blockquote class="blockquote">
					<p>
						$("#dp").jqueryuiDatepicker({</br>
							dateFormat: 'yy-mm-dd',</br>
						});</br>
					</p>
				</blockquote>
				<figcaption class="blockquote-footer">
					For all jQuery-UI method, u can prefix it with "jquery", this to avoid a conflicts between bootstrap method and jQuery-UI method.
				</figcaption>
			</figure>
		</div>

		<div class="col-sm-12 row justify-content-center align-items-center my-1 m-0 border border-success">
			<div class="form-group row col-sm-12 border border-primary m-1 @error('test') has-error @enderror">
				<label for="editor" class="col-form-label col-sm-4">Textarea : </label>
				<div class="col-sm-8 my-auto">
					<textarea name="test" id="editor" class="form-control form-control-sm col-sm-12 @error('test') is-invalid @enderror" placeholder="Textarea">{{ old('test', @$variable->test) }}</textarea>
					@error('test')
					<div class="invalid-feedback">
						{{ $message }}
					</div>
					@enderror
				</div>
			</div>
		</div>

		<div class="col-sm-12 row justify-content-center align-items-center my-2 m-0 border border-success">
			<div class="col-sm-4 my-auto">
				<button type="button" id="button1" class="m-1 btn btn-primary"><i class="fa-regular fa-user fa-beat"></i> Primary button</button>
				<button type="button" id="button2" class="m-1 btn btn-secondary"><i class="fa-solid fa-bomb fa-beat"></i> secondary button</button>
				<button type="button" id="button3" class="m-1 btn btn-outline-primary"><i class="bi bi-airplane-engines"></i> third button</button>
				<button type="button" id="button4" class="m-1 btn btn-outline-primary"><span class="mdi mdi-ab-testing"></span> fourth button</button>
			</div>
		</div>

		<div class="col-sm-12 row justify-content-center align-items-center my-2 m-0 border border-success">
			<h2>1 And 2 Tier Dynamic Inputs (with Form)</h2>
			@include('_form')
		</div>

		<div class="col-sm-12 row justify-content-center align-items-center my-2 m-0 border border-success">
			<table id="table_id" class="table table-sm table-hover">
				<thead>
					<tr>
						<th>Name</th>
						<th>Position</th>
						<th>Office</th>
						<th>Age</th>
						<th>Start date</th>
						<th>Salary</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>Tiger Nixon</td>
						<td>System Architect</td>
						<td>Edinburgh</td>
						<td>61</td>
						<td>25 Apr 2011</td>
						<td>$320,800</td>
					</tr>
					<tr>
						<td>Garrett Winters</td>
						<td>Accountant</td>
						<td>Tokyo</td>
						<td>63</td>
						<td>25 Jul 2011</td>
						<td>$170,750</td>
					</tr>
					<tr>
						<td>Ashton Cox</td>
						<td>Junior Technical Author</td>
						<td>San Francisco</td>
						<td>66</td>
						<td>12 Jan 2009</td>
						<td>$86,000</td>
					</tr>
					<tr>
						<td>Cedric Kelly</td>
						<td>Senior Javascript Developer</td>
						<td>Edinburgh</td>
						<td>22</td>
						<td>29 Mar 2012</td>
						<td>$433,060</td>
					</tr>
					<tr>
						<td>Airi Satou</td>
						<td>Accountant</td>
						<td>Tokyo</td>
						<td>33</td>
						<td>28 Nov 2008</td>
						<td>$162,700</td>
					</tr>
					<tr>
						<td>Brielle Williamson</td>
						<td>Integration Specialist</td>
						<td>New York</td>
						<td>61</td>
						<td>2 Dec 2012</td>
						<td>$372,000</td>
					</tr>
					<tr>
						<td>Herrod Chandler</td>
						<td>Sales Assistant</td>
						<td>San Francisco</td>
						<td>59</td>
						<td>6 Aug 2012</td>
						<td>$137,500</td>
					</tr>
					<tr>
						<td>Rhona Davidson</td>
						<td>Integration Specialist</td>
						<td>Tokyo</td>
						<td>55</td>
						<td>14 Oct 2010</td>
						<td>$327,900</td>
					</tr>
					<tr>
						<td>Colleen Hurst</td>
						<td>Javascript Developer</td>
						<td>San Francisco</td>
						<td>39</td>
						<td>15 Sep 2009</td>
						<td>$205,500</td>
					</tr>
					<tr>
						<td>Sonya Frost</td>
						<td>Software Engineer</td>
						<td>Edinburgh</td>
						<td>23</td>
						<td>13 Dec 2008</td>
						<td>$103,600</td>
					</tr>
					<tr>
						<td>Jena Gaines</td>
						<td>Office Manager</td>
						<td>London</td>
						<td>30</td>
						<td>19 Dec 2008</td>
						<td>$90,560</td>
					</tr>
					<tr>
						<td>Quinn Flynn</td>
						<td>Support Lead</td>
						<td>Edinburgh</td>
						<td>22</td>
						<td>3 Mar 2013</td>
						<td>$342,000</td>
					</tr>
					<tr>
						<td>Charde Marshall</td>
						<td>Regional Director</td>
						<td>San Francisco</td>
						<td>36</td>
						<td>16 Oct 2008</td>
						<td>$470,600</td>
					</tr>
					<tr>
						<td>Haley Kennedy</td>
						<td>Senior Marketing Designer</td>
						<td>London</td>
						<td>43</td>
						<td>18 Dec 2012</td>
						<td>$313,500</td>
					</tr>
					<tr>
						<td>Tatyana Fitzpatrick</td>
						<td>Regional Director</td>
						<td>London</td>
						<td>19</td>
						<td>17 Mar 2010</td>
						<td>$385,750</td>
					</tr>
				</tbody>
			</table>
		</div>

		<div class="col-sm-12 row justify-content-center align-items-center my-2 m-0 border border-success">
			<canvas id="myChart"></canvas>
		</div>

		<div class="col-sm-12 row justify-content-center align-items-center my-2 m-0 border border-success">
			<div id="calendar"></div>
		</div>

	</form>




		</div>
		<div class="card-footer d-flex justify-content-end">
			php artisan make:module Admin\TestingAll
		</div>
	</div>

</div>
@endsection

@section('js')
@include('_js')
@endsection
