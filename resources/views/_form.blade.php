<h2>Experiences (1 Tiers Dynamic Input)</h2>
<div id="experience_wrap" class="row @error('experiences') is-invalid @enderror "></div>
@error('experiences')
	<div class="invalid-feedback">
		{{ $message }}
	</div>
@enderror
<div class="col-12-sm">
	<button type="button" id="experience_add" class="btn btn-sm btn-outline-info">+ Add Experience</button>
</div>
<hr>
<h2>Dynamic Skills with Sub-skills (2 Tiers Dynamic Input)</h2>
<div id="skills_wrap" class="row @error('skills') is-invalid @enderror"></div>
@error('skills')
	<div class="invalid-feedback">
		{{ $message }}
	</div>
@enderror
<div class="col-12-sm">
	<button type="button" id="skills_add" class="m-1 btn btn-sm btn-outline-primary">+ Add Skill</button>
</div>
<hr>
<h2>Dynamic Countries and States (1 Tiers Dynamic Input with Select2)</h2>
<div id="countries_wrap" class="row @error('countries') is-invalid @enderror"></div>
@error('countries')
	<div class="invalid-feedback">
		{{ $message }}
	</div>
@enderror
<div class="col-12-sm">
	<button type="button" id="countries_add" class="m-1 btn btn-sm btn-outline-primary">+ Add Countries</button>
</div>
<hr>
<div class="col-12-sm d-flex justify-content-center">
	<button type="submit" class="m-1 btn btn-sm btn-outline-primary"><i class="fa-regular fa-floppy-disk"></i>&nbsp;Submit</button>
</div>
