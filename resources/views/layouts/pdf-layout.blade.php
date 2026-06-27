<?php
// \Auth::user()->setConnection('mysql3');
\Auth::user()->belongstostaff->unreadNotifications->markAsRead();

// function imageToBase64($path)
// {
// 	if (!file_exists($path)) {
// 		return null;
// 	}
// 	$type = pathinfo($path, PATHINFO_EXTENSION);
// 	$data = file_get_contents($path);
// 	return 'data:image/' . $type . ';base64,' . base64_encode($data);
// }

// $basePath = dirname(base_path()).'\\Storage\\app\\public\\web\\';
// $bg   = imageToBase64($basePath . 'background.jpg');
// $logo = imageToBase64($basePath . 'main-logo1.png');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>{{ config('app.name', 'Laravel') }}</title>
	<style>
		* { margin: 0; padding: 0; box-sizing: border-box; }
		@page {
			size: A4;
			margin: 0;
		}

		html, body {
			width: 210mm;
			/*height: 297mm; /* causing overflow */*/
			font-size: 12px;
			font-family: Arial, sans-serif;
			/*margin-top: 32mm;*/
			/*margin-bottom: 20mm;*/
			/*padding: 0 15mm;*/
		}

		@font-face {
			font-family: 'FreestyleScript';
			src: url("{{ public_path('pdf/FREESCPT.TTF') }}") format('truetype');
				font-weight: normal;
				font-style: normal;
			}

			.ewording {
				font-family: 'FreestyleScript', cursive;
				font-size: 28px;
			}

			/* Background */
			.bg {
				position: fixed;
				top: 0;
				left: 0;
				width: 210mm;
				height: 297mm;
				z-index: -1;
			}
			.bg img {
				width: 100%;
				height: 100%;
				object-fit: cover;
				opacity: 0.25;
			}

			/* Header */
			.header {
				position: fixed;
				top: 0;
				left: 0;
				right: 0;
				height: 25mm;
				text-align: center;
				font-size: 16px;
				font-weight: bold;
				border-bottom: 1px solid #ccc;
				padding-top: 6mm;
			}
			.header .logo {
				height: 15mm;
				vertical-align: middle;
				margin-right: 8px;
			}

			/* Footer */
			.footer {
				position: fixed;
				bottom: 10mm;
				left: 0;
				right: 0;
				text-align: center;
				font-size: 10px;
				color: #555;
			}
			.footer-box {
				display: inline-block;
				padding: 2px 10px;
				border: 1px solid #333;
				border-radius: 6px;
				background: #f9f9f9;
			}

			/* Main content */
			.content {
				margin-top: 32mm;
				margin-bottom: 20mm;
				padding: 0 20mm;
			}

			/* Paragraph styles with font size */
			p {
				font-size: 14px;
				line-height: 1.6;
				margin-bottom: 15px;
			}

			/* Bold and underline styles */
			.bold {
				font-weight: bold;
			}

			.red {
				color: red;
			}

			.underline {
				text-decoration: underline;
			}

			/* Unordered list styles */
			ul {
				list-style-type: disc;
				margin-left: 20px;
				margin-bottom: 15px;
				font-size: 14px; /* Set font size for unordered lists */
			}

			/* Ordered list styles */
			ol {
				list-style-type: decimal;
				margin-left: 20px;
				margin-bottom: 15px;
				font-size: 14px; /* Set font size for ordered lists */
			}

			table {
				width: 100%;
				border-collapse: collapse;
				margin-bottom: 20px;
				font-size: 14px;
				page-break-inside: auto;
			}
			th, td {
				border: 1px solid #ccc;
				padding: 8px;
				text-align: left;
			}
			th {
				background-color: #d9e9ff;
			}
			tr:nth-child(even) {
				background-color: #f7f7f7;
				page-break-inside: avoid;
			}
			.bold { font-weight: bold; }
			.red { color: red; }
			.center { text-align: center; }

			/* table-no-border  */
			.tnb {
				border-collapse: collapse;
				page-break-inside: auto;
			}

			.tnb th,
			.tnb td {
				border: none !important;
				background: transparent !important;
				padding: 4px 8px; /* optional, adjust spacing */
			}

			.tnb tr {
				background: transparent !important;
				page-break-inside: avoid;
			}

			.tnb tr:nth-child(even) {
				background: transparent !important;
				page-break-inside: avoid;
			}

			.tnb td {
				padding: 2px 6px;
				vertical-align: top;
			}

			.page-break {
				page-break-before: always;

			}

			.after-break {
				margin-top: 32mm;
			}
		</style>
	</head>
	<body>
		<!-- Background -->
		<div class="bg">
			<img src="{{ public_path('pdf/background.jpg') }}" alt="background">
		</div>

		<!-- Header -->
		<div class="header">
			<img src="{{ public_path('pdf\main-logo1.png') }}" class="logo">
			<br/>
			@yield('title', 'Document')
		</div>

		<!-- Content -->
		<div class="content">
			@yield('content')
		</div>

		<!-- Footer -->
		<div class="footer">
			<div class="footer-box">
				&copy; {{ config('app.name', 'Laravel') }} {{ now()->format('Y') }}
			</div>
		</div>
	</body>
	</html>
