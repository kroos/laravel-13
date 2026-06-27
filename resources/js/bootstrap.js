// routing js
import { loadModule } from './moduleLoader';

$(async function () {

	/* ================================
	 * 1️⃣ CSRF HEADER (GLOBAL, ONCE)
	 * ================================ */
	const token = document
	.querySelector('meta[name="csrf-token"]')
	?.getAttribute('content');

	if (!token) {
		console.error(
		              'CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token'
		              );
		return;
	}

	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': token
		}
	});

	/* ================================
	 * 2️⃣ SANCTUM COOKIE (ONCE)
	 * ================================ */
	try {
		await $.get('/sanctum/csrf-cookie');
	} catch (e) {
		console.warn('Sanctum CSRF cookie failed');
	}

	/* ================================
	 * 3️⃣ AUTO LOAD ROUTE JS
	 * ================================ */
	const route = document.body.dataset.route;
	await loadModule(route);

});
