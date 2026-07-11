const { route, url, requestid, sessionlastBatchId } = window.data;

$.fn.dataTable.moment( 'D MMM YYYY' );
$.fn.dataTable.moment( 'YYYY' );
$.fn.dataTable.moment( 'h:mm a' );

var table = $('#jb').DataTable({
	lengthMenu: [[50, 100, -1], [50, 100, 'All']],
	columnDefs: [
		{ type: 'date', targets: [6] },
	],
	order: [[6, 'desc']],
	responsive: true,
	autoWidth: false,
	fixedHeader: true,
	// dom: 'Bfrtip',
	ajax: {
		type: 'GET',
		url: route.getJobBatchTable,
		dataSrc: '',
		data: function(da){
		},
	},
	columns: [
		{ data: 'name', title: 'Name', defaultContent: '-', orderable: false, searchable:false, className: 'text-center', defaultContent: '-' },
		{ data: 'pending', title: 'Pending', defaultContent: '-', orderable: false, searchable:false, className: 'text-center', defaultContent: '-' },
		{ data: 'success', title: 'Success', defaultContent: '-', orderable: false, searchable:false, className: 'text-center', defaultContent: '-' },
		{ data: 'failed', title: 'Failed', defaultContent: '-', orderable: false, searchable:false, className: 'text-center', defaultContent: '-' },
		{ data: 'totalJobs', title: 'Total Jobs', defaultContent: '-', orderable: false, searchable:false, className: 'text-center', defaultContent: '-' },
		{ data: 'processedJobs', title: 'Processed Jobs', defaultContent: '-', orderable: false, searchable:false, className: 'text-center', defaultContent: '-' },
		{ data: 'created_at', title: 'Dates', defaultContent: '-', className: 'text-center', defaultContent: '-' },
	],
	initComplete: function(settings, response) {
		// console.log(response); // This runs after successful loading
	}
});

// Start polling
if (requestid || sessionlastBatchId) {
	const progressInterval = setInterval(checkProgress, 50);
	function checkProgress() {
		$.ajax({
			url: route.getProgress,
			data: {
				id: requestid ?? sessionlastBatchId
			},
			type: "GET",
			dataType: 'json',
			success: function(response) {
				const percent = Number(response.percent);
				// Safety check
				if (isNaN(percent)) return;
				// Update progress bar
				$('.progress').attr('aria-valuenow', percent).css('width', percent + '%');
				$(".csvprogress").css('width', percent + '%').text(percent + '%');
				$("#csvuploadStatus").html('<i class="fa-solid fa-spinner fa-spin-pulse fa-beat-fade"></i> Please wait..');
				$('#processedJobs').html(`<span>${response.processedJobs}</span> completed out of <span>${response.totalJobs}</span> process`);
				// reload DataTable without resetting paging
				table.ajax.reload(null, false);
				console.log("Progress:", percent);
				// Done?
				if (percent >= 100) {
					clearInterval(progressInterval);
					// redirect to download file
					window.location.href = route.downloadCSV;
				}
			},
			error: function(jqXHR, textStatus) {
				console.log("Progress error:", textStatus);
				// console.log("Progress error:", jqXHR);
			}
		});
		// $('#jb').DataTable().ajax.reload(null, false);
		table.ajax.reload(null, false);
	}
}
