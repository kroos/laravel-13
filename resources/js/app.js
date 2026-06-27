import './jquery';

// jquery-ui
import './jquery-ui-prefix';

// careful between these 2 (bootstrap and jquery-ui), cause it conflicts on "tooltips". this way, we override all components from jquery-ui which results we always use the bootstrap components.
// Added: Actual Bootstrap JavaScript dependency
import * as bootstrap from '../../node_modules/bootstrap/dist/js/bootstrap.bundle';

// select2
import select2 from 'select2';
select2();

// sweetalert2
import swal from 'sweetalert2';
window.swal = swal;

// moment
import moment from 'moment';
window.moment = moment;

// datatables
// import DataTable from 'datatables.net';
import DataTable from 'datatables.net-bs5';
import 'datatables.net-responsive-bs5';
import 'datatables.net-fixedheader-bs5';
import 'datatables.net-colreorder-bs5';
import 'datatables.net-autofill-bs5';
import jszip from 'jszip';
import pdfmake from 'pdfmake';
import 'pdfmake/build/vfs_fonts';
import 'datatables.net-buttons-bs5';
import 'datatables.net-buttons/js/buttons.html5.mjs';
import 'datatables.net-buttons/js/buttons.print.mjs';
DataTable.use(bootstrap);
DataTable.Buttons.jszip(jszip);
DataTable.Buttons.pdfMake(pdfmake);
window.DataTable = DataTable;

// minicolors
import '@claviska/jquery-minicolors';

// Chart.js
import  './chart';

// fullcalendar
import './fullcalendar';

// bootstrap validator 5
import	'bootstrapvalidator5';

// addRemoveRow
import	'addremrow-validator5-swal2-ajax';
// import	'addremrow';

// datatable plugin
import	'./dataTable-any-number.js';
import	'./dataTable-moment.js';

/* ckeditor */
import	'./ckeditor5';

// bootstrap
import	'./bootstrap';

// plugin variable
import config from './config/plugins';
window.config = config;
