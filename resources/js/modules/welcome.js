///////////////////////////////////////////////////////////////////////////////////////////
const { routes, old, now, errors } = window.data;
function getError(name) {
	return errors[name] ? errors[name][0] : null;
}
// console.log(moment(now).add(2, 'days').format('YYYY-MM-DD'));

///////////////////////////////////////////////////////////////////////////////////////////
$('#textarea_1, #editor').ckeditor({
	toolbar: 'advance'
});

///////////////////////////////////////////////////////////////////////////////////////////
// tooltip
$(document).ready(function(){
	$('[data-toggle="tooltip"]').tooltip();
});

///////////////////////////////////////////////////////////////////////////////////////////
$(`#minicolor`).minicolors({});

///////////////////////////////////////////////////////////////////////////////////////////
$('#button1').click(function(){
	alert("Thanks");
});

///////////////////////////////////////////////////////////////////////////////////////////
$('#button2').click(function(){
	swal.fire('Title', 'message', 'info');
});

///////////////////////////////////////////////////////////////////////////////////////////
console.log('test');

///////////////////////////////////////////////////////////////////////////////////////////
$('#select2').select2({
		...config.select2,
});

///////////////////////////////////////////////////////////////////////////////////////////
console.log(moment().format('D MMMM YYYY'));

///////////////////////////////////////////////////////////////////////////////////////////
$("#dp").jqueryuiDatepicker({
	...config.datepicker
}).on('change', function(){
	$('#form').bootstrapValidator('revalidateField', 'datepicker');
});

///////////////////////////////////////////////////////////////////////////////////////////
$.fn.dataTable.datetime('D MMM YYYY');
$('#table_id').DataTable({
	...config.datatable,
	columnDefs: [
		...(config.datatable.columnDefs || []),
		{ type: 'date', targets: [1] },
	],
	order: [[0, 'asc'], [1, 'asc']],
});

///////////////////////////////////////////////////////////////////////////////////////////

const ctx = document.getElementById('myChart');
// const ctx = $('#myChart');
new Chart(ctx, {
	type: 'bar',
	data: {
		labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
		datasets: [{
			label: '# of Votes',
			data: [12, 19, 3, 5, 2, 3],
			borderWidth: 1
		}]
	},
	options: {
		scales: {
			y: {
				beginAtZero: true
			}
		}
	}
});

///////////////////////////////////////////////////////////////////////////////////////////
let calendarEl = document.getElementById('calendar');
let calendar = new Calendar(calendarEl, {
	...config.fullcalendar,

	// events: {
		// 	url: '{{ route('dashboard') }}',
		// 	method: 'GET',
		// 	extraParams: {
			// 	},
	// },

		events: [
		{
			title: 'Event 1',
			start: now, // Date of the event
			description: 'Description of Event 1'
		},
		{
			title: 'Event 2',
			start: moment(now).add(2, 'days').format('YYYY-MM-DD'), // Date and time
			end: moment(now).subtract().format('YYYY-MM-DD'), // Optional end time
			description: 'Description of Event 2'
		},
		{
			title: 'Event 3',
			start: moment(now).add(6, 'days').format('YYYY-MM-DD'),
			description: 'Description of Event 3'
		}
		],
		eventClick: function(info) {
			// alert(info.event.title + "\n" + info.event.extendedProps.description);
			swal.fire({
				title: info.event.title,
				text: info.event.extendedProps.description,
				icon: 'info',
			});
		},
		eventDidMount: function(info) {
			$(info.el).tooltip({
				title: info.event.extendedProps.description,
				placement: 'top',
				trigger: 'hover',
				container: 'body'
			});
		},
		eventTimeFormat: { // like '14:30:00'
		hour: '2-digit',
		minute: '2-digit',
		second: '2-digit',
		hour12: true
	},

});
calendar.render();

///////////////////////////////////////////////////////////////////////////////////////////
// Experiences (fieldName "experiences")
$("#experience_wrap").addRemRow({

	/* add remove row full option */
	addBtn: "#experience_add",
	startRow: 0,
	maxRows: 10,
	removeClass: "exp_remove",
	rowSelector: 'exp',
	fieldName: "experiences",


	/* ewindex full option */
	reindexRowName: ['CAttrib1'],		// pattern => ${name}[${i}][product]
	reindexRowID: ['CAttrib2'],			// pattern => userdefined_${i}
	reindexRowIndex: ['CAttrib3'],	// pattern => ${i}

	/* BootstrapValidator full option */
	validator: {
		form: '#form',
		fields: {
			'[name]': {
				validators: {
					notEmpty: {
						message: 'Please insert name'
					}
				}
			},
			'[nameid]': {
				validators: {
					notEmpty: {
						message: 'Please insert name ID'
					},
					// stringLength: {
					// 	value: 0,
					// 	message: 'Quantity must be equal or greater than 0. '
					// },
				}
			},
		}
	},

	/* SweetAlert2 and AJAX full option */
	swal: {
		options: {
			...config.swal,
		},
		ajax: {
   		dbPrimaryKeyId: 'id',
			url: `api/person`,
			method: 'DELETE',
			dataType: 'json',
			data: {
			}
		}
	},

	rowTemplate: (i, name) => `
	<div class="col-sm-12 row g-3 m-1 exp" id="exp_${i}" CAttrib3="${i}">
		<input type="hidden" name="${name}[${i}][id]" >
		<div class="form-group form-floating col-sm-4 ${getError(`${name}.${i}.name`) ? 'is-invalid' : ''}">
			<input
			 type="text"
			 name="${name}[${i}][name]"
			 id="name_${i}"
			 class="form-control form-control-sm ${getError(`${name}.${i}.name`) ? 'is-invalid' : ''}"
			 CAttrib2="customValue2_${i}"
			>
			<label for="name_${i}" class="form-col-label" CAttrib1="custom[${i}][Value1]">
			Name : </label>
			${getError(`${name}.${i}.name`) ? `
			<div class="invalid-feedback">
				${getError(`${name}.${i}.name`)}
			</div>
		` : ''}
		</div>
		<div id="gar_${i}" class="col-sm-4">
		</div>
		<div class="col-sm-1">
			<button class="btn btn-sm btn-outline-danger exp_remove" data-id="${i}"><i class="fa-solid fa-xmark fa-beat"></i></button>
		</div>
	</div>
	`,
	onAdd: (i, e, $r, name) => {
		// console.log("Experience added:", `exp_${i}`, e, $r, name);
		populateCheckbox(i, name);
	},
	onRemove: async (i, event, $row, name) => {
		// console.log("Experience removed:", `exp_${i}`);
	},
});

// checkbox
function populateCheckbox(i = 0, name = '', getCountries = []) {

	// getOptSalesgetCountries
	$.ajax({
		url: routes.countries,
		dataType: 'json',
		type: "GET",
		success: function (response) {

			const $checkbox = $("#gar_"+i);
			if($checkbox.length > 0) $checkbox.empty();

			// console.log(getCountries);
			const obj = Array.isArray(getCountries) ? getCountries : Object.entries(getCountries);
			// console.log(obj);

			const cgetCountriess = obj.map(item =>  item[1]);

			response.forEach(function(value, j) {
				const checkboxId = `jdescitem${i}_${j}`;

				// Check if this module_id exists in cicms
				let found = cgetCountriess.find(m => m.text == value.id);

				// If found, mark checked
				let isChecked = found ? 'checked' : '';

				let row = `
					<div class="form-check">
						<input
							type="checkbox"
							name="${name}[${i}][gItems][${j}][text]"
							value="${value.id}"
							id="${checkboxId}"
							class="form-check-input "
							${isChecked}
						>
						<label
							class="form-check-label"
							for="${checkboxId}"
						>
							${value.text}
						</label>
					</div>
				`;
				$checkbox.append(row);
			});
		},
		error: function (jqXHR, textStatus, errorThrown) {
			// console.log(textStatus, errorThrown);
		}
	});

}



///////////////////////////////////////////////////////////////////////////////////////////
// 2 tier dynamic input
$("#skills_wrap").addRemRow({
	addBtn: "#skills_add",
	maxRows: 3,
	fieldName: "skills",
	rowSelector: "skill",
	removeClass: "skill_remove",
	nestedwrapper: `.nwrap`,

	reindexRowName: ['customName'],
	reindexRowID: ['customID'],
	reindexRowIndex:['customIndex'],


	rowTemplate: (i, name) => `
	<div class="col-sm-12 m-1 row border border-primary rounded skill" id="skill_${i}">
		<input type="hidden" name="${name}[${i}][id]" value="">
		<div class="col-sm-12 form-group m-0 row ${getError(`${name}.${i}.name`) ? 'is-invalid' : ''}">
			<label for="name_${i}" class="form-col-label col-sm-3">Name #${i+1}</label>
			<div class="col-sm-9 row" customName="${name}[${i}][customName]">
				<div class="col-sm-10 my-auto" customIndex="${i}" customID="customID_${i}">
					<input
					 type="text"
					 name="${name}[${i}][name]"
					 value=""
					 id="name_${i}"
					 class="form-control form-control-sm ${getError(`${name}.${i}.name`) ? 'is-invalid' : ''}"
					 placeholder="Name ${i+1}"
					>
					${getError(`${name}.${i}.name`) ? `
					<div class="invalid-feedback">
						${getError(`${name}.${i}.name`)}
					</div>
				` : ''}
				</div>
			</div>
		</div>
		<div class="col-sm-12 form-group m-0 row ${getError(`${name}.${i}.skill`) ? 'has-error' : ''}">
			<label for="sk_${i}" class="form-col-label col-sm-3">Skill #${i+1}</label>
			<div class="col-sm-9 row my-auto">
				<div class="col-sm-10 m-0">
					<input
					 type="text"
					 name="${name}[${i}][skill]"
					 value="" id="sk_${i}"
					 class="form-control form-control-sm ${getError(`${name}.${i}.skill`) ? 'is-invalid' : ''}"
					 placeholder="Skill ${i+1}"
					>
					${getError(`${name}.${i}.skill`) ? `
					<div class="invalid-feedback">
						${getError(`${name}.${i}.skill`)}
					</div>
				` : ''}
				</div>
				<div class="col-sm-1 m-1">
					<button class="btn btn-sm btn-outline-danger skill_remove" data-index="${i}">
						<i class="fa-regular fa-trash-can fa-beat"></i>
					</button>
				</div>
			</div>
		</div>

		<!-- Sub-skills wrapper -->
		<div class="col-sm-9 offset-sm-3 my-1 border border-primary-subtle rounded">
			<div id="subskill_wrap_${i}" class="nwrap row ">
			</div>
			<button type="button" id="subskill_add_${i}" class="m-1 btn btn-sm btn-primary">+ Add Sub-skill</button>
		</div>

	</div>
	`,
	onAdd: (i, e, $row1, name) => {
		console.log("Skill added:", "skill_"+i, $row1);

		const $field1 = $row1.find(`[name="${name}[${i}][name]"]`);
		const $field2 = $row1.find(`[name="${name}[${i}][skill]"]`);

		// $field1.css({"color": "red", "border": "2px solid red"});

		$('#form').bootstrapValidator('addField', $field1, {
			validators: {
				notEmpty: {
					message: 'Please insert name.'
				}
			}
		});
		$('#form').bootstrapValidator('addField', $field2, {
			validators: {
				notEmpty: {
					message: 'Please insert skill.'
				}
			}
		});


		// initialize sub-skills for this skill
		$(`#subskill_wrap_${i}`).addRemRow({
			addBtn: `#subskill_add_${i}`,
			maxRows: 5,
			fieldName: `skills[${i}][subskills]`,
			rowSelector: `subskill_${i}`,
			removeClass: "subskill_remove",
			rowTemplate: (j, name) => `
			<div class="col-sm-12 m-1 row border border-info-subtle rounded subskill_${i}" id="subskill_${i}_${j}">
				<input type="hidden" name="${name}[${j}][id]" value="">
				<div class="col-sm-12 form-group m-1 row ${getError(`${name}.${i}.subskill`) ? 'has-error' : ''}">
					<label for="sbsk_${j}" class="form-col-label col-sm-2">Sub-skill #${j+1}</label>
					<div class="col-sm-8 my-auto">
						<input
						 type="text"
						 name="${name}[${j}][subskill]"
						 value=""
						 id="sbsk_${j}"
						 class="form-control form-control-sm ${getError(`${name}.${i}.subskill`) ? 'is-invalid' : ''}"
						 placeholder="Sub-skill ${j+1}"
						>
						${getError(`${name}.${i}.subskill`) ? `
						<div class="invalid-feedback">
							${getError(`${name}.${i}.subskill`)}
						</div>
					` : ''}
					</div>
				</div>

				<div class="col-sm-12 form-group m-1 row ${getError(`${name}.${i}.years`) ? 'has-error' : ''}">
					<label for="sbsky_${j}" class="form-col-label col-sm-2">Years #${j+1}</label>
					<div class="col-sm-8 my-auto">
						<input
						 type="text"
						 name="${name}[${j}][years]"
						 value="" id="sbsky_${j}"
						 class="form-control form-control-sm ${getError(`${name}.${i}.years`) ? 'is-invalid' : ''}"
						 placeholder="Years ${j+1}"
						>
						${getError(`${name}.${i}.years`) ? `
						<div class="invalid-feedback">
							${getError(`${name}.${i}.years`)}
						</div>
					` : ''}
					</div>
					<div class="col-sm-1">
						<button class="btn btn-sm btn-outline-danger subskill_remove" data-index="${j}">
							<i class="fa-regular fa-trash-can fa-beat"></i>
						</button>
					</div>
				</div>
			</div>
			`,
			onAdd: (j, e, $row2, name) => {

				console.log("Sub-skill added:", `skill_${i}_${j}`, $row2);

				const $field1 = $row2.find(`[name="${name}[${j}][subskill]"]`);
				const $field2 = $row2.find(`[name="${name}[${j}][years]"]`);

				$('#form').bootstrapValidator('addField', $field1, {
					validators: {
						notEmpty: {
							message: 'Please insert subskill.'
						}
					}
				});
				$('#form').bootstrapValidator('addField', $field2, {
					validators: {
						notEmpty: {
							message: 'Please insert years.'
						}
					}
				});


			},
			onRemove: async (j, event, $row2, name) => {
				console.log("Sub-skill removed:", `skill_${i}_${j}`);

				const $field1 = $row2.find(`[name="${name}[${j}][subskill]"]`);
				const $field2 = $row2.find(`[name="${name}[${j}][years]`);

				const idv = $row2.find(`input[name="${name}[${j}][id]"]`).val();
				if (!idv) {
					$('#form').bootstrapValidator('removeField', $field1);
					$('#form').bootstrapValidator('removeField', $field2);
					return true;
				}

				let dbId = idv;
				const result = await swal.fire({
					...config.swal,
				});

				// ❌ Cancel clicked
				if (result.isDismissed) {
					await swal.fire('Cancelled', 'Your data is safe from delete', 'info');
					return false;
				}

				// 2️⃣ Perform AJAX delete
				try {
					const response = await $.ajax({
						type: 'DELETE',
						url: `${routes.url}/${dbId}`,
						data: {
							id: dbId
						},
						dataType: 'json'
					});
					await swal.fire('Deleted!', response.message, response.status);
					$('#form').bootstrapValidator('removeField', $field1);
					$('#form').bootstrapValidator('removeField', $field2);
					return true; // ✅ ALLOW plugin to remove row
				} catch (e) {
					await swal.fire('Ajax Error', 'Something went wrong with ajax!', 'error');
					return false; // ❌ BLOCK removal
				}

			}
		});
	},
	onRemove: async (i, event, $row, name) => {
		console.log("Skill removed:", `skill_${i}`);

		const $field1 = $row.find(`[name="${name}[${i}][name]"]`);
		const $field2 = $row.find(`[name="${name}[${i}][skill]"]`);

		const idv = $row.find(`input[name="${name}[${i}][id]"]`).val();
		if (!idv) {
			$('#form').bootstrapValidator('removeField', $field1);
			$('#form').bootstrapValidator('removeField', $field2);
			return true;
		}
		let dbId = idv;
		const result = await swal.fire({
			...config.swal,
		});

		// ❌ Cancel clicked
		if (result.isDismissed) {
			await swal.fire('Cancelled', 'Your data is safe from delete', 'info');
			return false;
		}

		// 2️⃣ Perform AJAX delete
		try {
			const response = await $.ajax({
				type: 'DELETE',
				url: `${routes.url}/${dbId}`,
				data: {
					_token: token,
					id: dbId
				},
				dataType: 'json'
			});
			await swal.fire('Deleted!', response.message, response.status);
			$('#form').bootstrapValidator('removeField', $field1);
			$('#form').bootstrapValidator('removeField', $field2);
			return true; // ✅ ALLOW plugin to remove row
		} catch (e) {
			await swal.fire('Ajax Error', 'Something went wrong with ajax!', 'error');
			return false; // ❌ BLOCK removal
		}

	}
});

///////////////////////////////////////////////////////////////////////////////////////////
// Countries (fieldName "countries")
let selectedStates = []; // globally track selected state IDs
$("#countries_wrap").addRemRow({
	addBtn: "#countries_add",
	maxRows: 3,
	removeClass: "country_remove",
	fieldName: "countries",
	rowSelector: "ctry",
	rowTemplate: (i, name) => `
		<div class="col-sm-12 row m-0 my-1 border border-warning-subtle rounded ctry" id="ctry_${i}">
			<input type="hidden" name="${name}[${i}][id]" value="">
			<div class="col-sm-10 form-group row m-0 my-1 ${getError(`${name}.${i}.country_id`) ? 'has-error' : ''}">
				<label for="country_${i}" class="col-sm-2 form-col-label">Country : </label>
				<div class="col-sm-10">
					<select
					 name="${name}[${i}][country_id]"
					 id="country_${i}"
					 class="form-select form-select-sm ${getError(`${name}.${i}.country_id`) ? 'is-invalid' : ''}"
					>
						<option value="">Please choose</option>
					</select>
					${getError(`${name}.${i}.country_id`) ? `
					<div class="invalid-feedback">
						${getError(`${name}.${i}.country_id`)}
					</div>
				` : ''}
				</div>
			</div>
			<div class="col-sm-10 form-group row m-0 my-1 ${getError(`${name}.${i}.state_id`) ? 'has-error' : ''}">
				<input type="hidden" name="${name}[${i}][id]" value="">
				<label for="state_${i}" class="col-sm-2 form-col-label">State : </label>
				<div class="col-sm-9 my-auto">
					<select
					 name="${name}[${i}][state_id]"
					 id="state_${i}"
					 class="form-select form-select-sm ${getError(`${name}.${i}.state_id`) ? 'is-invalid' : ''}"
					>
						<option value="">Please choose</option>
					</select>
					${getError(`${name}.${i}.state_id`) ? `
					<div class="invalid-feedback">
						${getError(`${name}.${i}.state_id`)}
					</div>
				` : ''}
				</div>
				<div class="col-sm-1">
					<button class="btn btn-sm btn-outline-danger country_remove" data-index="${i}">
						<i class="fa-regular fa-trash-can fa-beat"></i>
					</button>
				</div>
			</div>
		</div>
	`,
	onAdd: (i, e, $row, name) => {
		console.log("Country added:", `ctry_${i}`, $row);

		const $country = $('#country_' + i);
		const $state = $('#state_' + i);

		// Initialize country select2
		$country.select2({
			...config.select2,
			ajax: {
				url: routes.countries,
				dataType: 'json',
				processResults: data => ({ results: data })
			}
		});

		// Initialize empty state select2
		$state.select2({
			...config.select2,
		});

		// When country changes, reload states dynamically
		$country.on('change', function () {
			const countryId = $(this).val();
			$state.val(null).trigger('change');
			if (countryId) {
				$state.select2({
					...config.select2,
					ajax: {
						url: `${routes.states}/${countryId}`,
						dataType: 'json',
						processResults: data => ({
							results: data.filter(item => !selectedStates.includes(String(item.id)))
						})
					}
				});
			}
		});

		// Track selected state
		let previousState = null;

		$state.on('select2:select', function (e) {
			const stateId = e.params.data.id;
			// Remove previous (if any) and add new
			if (previousState) {
				selectedStates = selectedStates.filter(id => id !== String(previousState));
			}
			selectedStates.push(String(stateId));
			previousState = stateId;
			// Refresh all state dropdowns so they re-filter
			refreshAllStateDropdowns();
		});

		$state.on('select2:clear', function () {
			// If cleared manually
			if (previousState) {
				selectedStates = selectedStates.filter(id => id !== String(previousState));
				previousState = null;
				refreshAllStateDropdowns();
			}
		});

		// Helper: reapply filters on all state select2s
		function refreshAllStateDropdowns() {
			$('#countries_wrap select[id^="state_"]').each(function () {
				const sel = $(this);
				const countryId = sel.closest('.row').find('select[id^="country_"]').val();
				if (countryId) {
					sel.select2({
						...config.select2,
						ajax: {
							url: `${routes.states}/${countryId}`,
							dataType: 'json',
							processResults: data => ({
								results: data.filter(item => !selectedStates.includes(String(item.id)) || sel.val() === String(item.id))
							})
						}
					});
				}
			});
		}

		const $field1 = $row.find(`[name="countries[${i}][country_id]"]`);
		const $field2 = $row.find(`[name="countries[${i}][state_id]"]`);

		$('#form').bootstrapValidator('addField', $field1, {
			validators: {
				notEmpty: {
					message: 'Please choose country.'
				}
			}
		});
		$('#form').bootstrapValidator('addField', $field2, {
			validators: {
				notEmpty: {
					message: 'Please choose state.'
				}
			}
		});


	},
	onRemove: async (i, event, $row, name) => {
		console.log("Country removed:", `ctry_${i}`);
		// When a row is removed, remove its selected state from tracking
		const stateVal = $(`#state_${i}`).val();
		if (stateVal) {
			selectedStates = selectedStates.filter(id => id !== String(stateVal));
		}

		const $field1 = $row.find(`[name="${name}[${i}][country_id]"]`);
		const $field2 = $row.find(`[name="${name}[${i}][state_id]"]`);

		const idv = $row.find(`[name="${name}[${i}][id]"]`).val();
		if (!idv) {
			$('#form').bootstrapValidator('removeField', $field1);
			$('#form').bootstrapValidator('removeField', $field2);
			return true;
		}
		let dbId = idv;
		const result = await swal.fire({
			...config.swal,
		});

		// ❌ Cancel clicked
		if (result.isDismissed) {
			await swal.fire('Cancelled', 'Your data is safe from delete', 'info');
			return false;
		}

		// 2️⃣ Perform AJAX delete
		try {
			const response = await $.ajax({
				type: 'DELETE',
				url: `${routes.url}/${dbId}`,
				data: {
					_token: `{{ csrf_token() }}`,
					id: dbId
				},
				dataType: 'json'
			});
			await swal.fire('Deleted!', response.message, response.status);
			$('#form').bootstrapValidator('removeField', $field1);
			$('#form').bootstrapValidator('removeField', $field2);
			return true; // ✅ ALLOW plugin to remove row
		} catch (e) {
			await swal.fire('Ajax Error', 'Something went wrong with ajax!', 'error');
			return false; // ❌ BLOCK removal
		}

	},
});

///////////////////////////////////////////////////////////////////////////////////////////
// restore after fail form process
$(function () {


	// === Restore old SKILLS ===
	const oldSkills = old.skills;
	if (oldSkills.length > 0) {
		oldSkills.forEach(function (skill, i) {
			$("#skills_add").trigger('click'); // simulate add skill
			const $skill = $("#skills_wrap").children().eq(i);

			// Fill skill name + main skill
			$skill.find(`input[name="skills[${i}][name]"]`).val(skill.name || '');
			$skill.find(`input[name="skills[${i}][skill]"]`).val(skill.skill || '');

			// === Restore SUB-SKILLS ===
			if (skill.subskills && skill.subskills.length > 0) {
				skill.subskills.forEach(function (sub, j) {
					$(`#subskill_add_${i}`).trigger('click'); // simulate add sub-skill
					const $sub = $(`#subskill_wrap_${i}`).children().eq(j);
					$sub.find(`input[name="skills[${i}][subskills][${j}][subskill]"]`).val(sub.subskill || '');
					$sub.find(`input[name="skills[${i}][subskills][${j}][years]"]`).val(sub.years || '');
				});
			}
		});
	}

	// === Restore old EXPERIENCES ===
	const oldExperiences = old.experiences;
	if (oldExperiences.length > 0) {
		oldExperiences.forEach(function (exp, i) {
			$("#experience_add").trigger('click'); // simulate add experience
			const $exp = $("#experience_wrap").children().eq(i);

			populateCheckbox(j, 'experiences', sajobDesc.gItems);

			$exp.find(`[name="experiences[${i}][name]"]`).val(exp.name || '');
			$exp.find(`[name="experiences[${i}][id]"]`).val(exp.id || '');
		});
	}

	// === Restore old COUNTRIES ===
	const oldCountries = old.countries;
	if (oldCountries.length > 0) {

		oldCountries.forEach(function (ctry, i) {
			// Add row dynamically
			$("#countries_add").trigger('click');

			// Grab the newly added row
			const $row = $("#countries_wrap").children().eq(i);

			const $country = $row.find(`select[name="countries[${i}][country_id]"]`);
			const $state = $row.find(`select[name="countries[${i}][state_id]"]`);

			// --- Restore Country ---
			if (ctry.country_id) {
				// Create option element manually
				const countryOption = new Option('Loading...', ctry.country_id, true, true);
				$country.append(countryOption).trigger('change');

				// Fetch actual country name asynchronously
				$.ajax({
					url: routes.countries,
					dataType: 'json'
				}).then(data => {
					const found = data.find(d => String(d.id) === String(ctry.country_id));
					if (found) {
						const option = new Option(found.text, found.id, true, true);
						$country.empty().append(option).trigger('change');
					}
				});
			}

			// --- Restore State ---
			if (ctry.state_id && ctry.country_id) {
				$.ajax({
					url: `api/states/${ctry.country_id}`,
					dataType: 'json'
				}).then(data => {
					const found = data.find(d => String(d.id) === String(ctry.state_id));
					if (found) {
						const option = new Option(found.text, found.id, true, true);
						$state.append(option).trigger('change');
					}
				});
			}
		});
	}

});

//////////////////////////////////////////////////////////////////////////////////////////
// bootstrap validator
$('#form').bootstrapValidator({
	fields: {
		switch: {
			validators: {
				notEmpty: {
					message: 'Please click.'
				},
			}
		},
		minicolor: {
			validators: {
				notEmpty: {
					message: 'Please choose color.'
				},
			}
		},
		datepicker: {
			validators: {
				notEmpty: {
					message: 'Please choose date.'
				},
				date: {
					format: 'YYYY-MM-DD',
					message: 'The date format is not valid. '
				}
			}
		},
		'image[]': {
			validators: {
				notEmpty: {
					message: 'Please select an image'
				},
				file: {
					extension: 'jpeg,jpg,png,bmp',
					type: 'image/jpeg,image/png,image/bmp',
					maxSize: 7990272,   // 3264 * 2448
					message: 'The selected file is not valid'
				}
			}
		},
		select2: {
			validators: {
				notEmpty: {
					message: 'Please choose'
				}
			}
		},
	}
});

////////////////////////////////////////////////////////////////////////////////////
