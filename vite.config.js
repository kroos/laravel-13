import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
// import { path  } from 'path';

export default defineConfig({
	plugins: [
		laravel({
			input: [
				'resources/scss/app.scss',
				'resources/css/app.css',
				'resources/js/app.js'
			],
			refresh: true,
		}),
	],
	// define: {
	// 	jQuery: 'window.jQuery',
	// 	$: 'window.jQuery'
	// },
	build: {
		chunkSizeWarningLimit: 5000,

		// only for javascript so.... no CSS
		// and also, not practical due to track all package.json
		// rollupOptions: {
		// 	output: {
		// 		manualChunks: {
		// 			vendorjs: [
		// 				'@claviska/jquery-minicolors',
		// 				'@fullcalendar/core',
		// 				'@fullcalendar/daygrid',
		// 				'@fullcalendar/list',
		// 				'@fullcalendar/moment',
		// 				'@fullcalendar/multimonth',
		// 				'@fullcalendar/timegrid',
		// 				'addremrow-validator5-swal2-ajax',
		// 				'bootstrapvalidator5',
		// 				'chart.js',
		// 				'ckeditor5',
		// 				'datatables.net',
		// 				'datatables.net-autofill-bs5',
		// 				'datatables.net-bs5',
		// 				'datatables.net-buttons-bs5',
		// 				'datatables.net-responsive-bs5',
		// 				'jquery',
		// 				'jquery-ui',
		// 				'jszip',
		// 				'moment',
		// 				'pdfmake',
		// 				'select2',
		// 				'select2-bootstrap-5-theme',
		// 				'sweetalert2',
		// 			]
		// 		}
		// 	}
		// },

		sourcemap: true,        // full source map
		// sourcemap: 'inline', // embed in JS file
		// sourcemap: 'hidden', // generate but don't expose in devtools
	},
});

