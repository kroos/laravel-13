const { routes, old } = window.data;

$('#opt').select2({
	...config.select2,
	ajax: {
		url: `${routes.getYesNoOptions}`,
		type: 'GET',
		dataType: 'json',
		data: function (params) {
			return {
				search: params.term,
			}
		},
		processResults: function (data) {
			return {
				results: data.map(function(item) {
					return {
						id: item.id,
						text: item.option,
						raw: item
					}
				})
			};
		}
	}
});

console.log(old.option);
if(!old.option){
	$.ajax({
		url: routes.getYesNoOptions,
		type: 'GET',
		dataType: 'json',
		data: {
			id: old.option,
		},
	}).then(data => {
		const found = data.find(d =>
			String(d.id) === String(option)
		);
		if (found) {
			const option = new Option(found.text, found.id, true, true);
			$country.empty().append(option).trigger('change');
		}
	});
}

