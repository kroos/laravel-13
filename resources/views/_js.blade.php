	<?php
	$items = @$variable
					?->hasmanyModel()
					?->get()
					->map(function ($items) {
						$modules = $items
											?->belongstomanyModel()
											?->get()
											->map(function ($module) {
												return [
													$module->pivot->id, [
														'item_id' => $module->id,
													]
												];
											})
											->toArray();

						return [
							'id'       => $items->id,
							'name'   => $items->name,
							'gItems'     => $modules,
						];
					})
					->toArray() ?? [];
	$salesJD = old('experiences', $items);
	// dd($salesJD);

$itemsB = @$variable?->hasmanyModel()?->get(['column'])?->toArray() ?? [];
?>

	window.data = {
		routes: {
			countries: "{{ route('countries') }}",
			states: "{{ url('api/states') }}",
			url: "{{ url('slippostage') }}"
		},
		old: {
			skills: @json(old('skills', @$variable?->hasmanyModel?->get()?->toArray() ?? [])),
			experiences: @json(old('experiences', $items)),
			countries: @json(old('countries', $itemsB))
		},
		now: "{{ now()->toIso8601String() }}",
		errors: @json($errors->toArray()),
	};
