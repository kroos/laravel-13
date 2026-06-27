const { route, url, old } = window.data;

function loadTable(){
	$.post(route.getTestingAdmin, function (response) {
		table.clear().rows.add(response).draw();
	}, 'json');
}

const kursus = [
		{ data: 'id', title: 'ID', defaultContent: '-', className: 'text-center', },
		{
			data: 'id',
			title: '#',
			className: 'text-center',
			defaultContent: '-',
			orderable: false,
			searchable:false,
			render: function(data){
				return `
					<div class="btn-group btn-group-sm" role="group">
						<a href="${url.testingAdmin}/${data}" class="btn btn-sm btn-outline-info" title="View">
							<i class="fa-regular fa-eye"></i>
						</a>

						<a href="${url.testingAdmin}/${data}/edit" class="btn btn-sm btn-outline-info" title="Edit">
							<i class="fa-regular fa-pen-to-square"></i>
						</a>

						<button type="button" data-id="${data}" title="Delete" class="delete_button btn btn-sm btn-outline-danger">
							<i class="fa-solid fa-trash-can"></i>
						</button>
					</div>
				`;
			}
		}
	];

const dtConfig = {
	...config.datatable,
	order: [[0, 'asc']/*, [1, 'asc']*/],
	data: [],
	columns: kursus
};

/* datatable */
$.fn.dataTable.moment( 'D MMM YYYY' );
$.fn.dataTable.moment( 'YYYY' );
$.fn.dataTable.moment( 'h:mm a' );

const table = $('#testingAdmin').DataTable(dtConfig);

$(document).on('click', '.delete_button', function(e){
	e.preventDefault();
	SwalDelete($(this).data('id'));
});

function SwalDelete(itemId){
	swal.fire({
	...config.swal,
		preConfirm: function()                {
			return new Promise(function(resolve) {
				$.ajax({
					url: `${url.testingAdmin}/${itemId}`,
					type: 'DELETE',
					data:	{
						id: itemId,
					},
					dataType: 'json'
				})
				.done(function(response){
					swal.fire('Deleted!', response.message, response.status);
					loadTable();
				})
				.fail(function(){
					swal.fire(config.swal.errorTitle, config.swal.errorMessage, config.swal.errorType);
 				});
			});
		},
	})
	.then((result) => {
		if(result.dismiss === swal.DismissReason.cancel) {
			swal.fire(config.swal.cancelTitle, config.swal.cancelMessage, config.swal.cancelType);
		}
	});
}

loadTable();
