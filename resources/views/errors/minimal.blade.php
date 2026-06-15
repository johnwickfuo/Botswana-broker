
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>@yield('title') - {{ $settings->site_name }}</title>

	@if(isset($settings) && $settings->favicon)
		<link rel="icon" href="{{ asset('storage/app/public/'.$settings->favicon) }}">
	@endif

	<!-- Google font -->
	<link href="https://fonts.googleapis.com/css?family=Montserrat:700,900" rel="stylesheet">

	<!-- Font Awesome Icon -->
	<link type="text/css" rel="stylesheet" href="{{ asset('error/css/font-awesome.min.css') }}" />

	<!-- Custom stlylesheet -->
	<link type="text/css" rel="stylesheet" href="{{ asset('error/css/style.css') }}" />

	<!-- Republic of Botswana brand layer (loaded last to override the theme) -->
	<link rel="stylesheet" href="{{ asset('css/botswana-brand.css') }}">
	<style>
		body {
			background-color: var(--bw-white, #fff);
			color: var(--bw-ink, #111);
			margin: 0;
		}
		.bw-error {
			min-height: 100vh;
			display: flex;
			align-items: center;
			justify-content: center;
			text-align: center;
			padding: 2rem 1.5rem;
		}
		.bw-error__inner {
			max-width: 36rem;
		}
		.bw-error__republic {
			font-size: .8rem;
			font-weight: 600;
			letter-spacing: .14em;
			text-transform: uppercase;
			color: var(--bw-blue, #00A3DD);
			margin-bottom: .75rem;
		}
		.bw-error__code {
			font-size: clamp(4rem, 14vw, 7rem);
			font-weight: 800;
			line-height: 1;
			color: var(--bw-ink, #111);
			margin: 0;
		}
		.bw-divider {
			width: 4rem;
			margin: 1.5rem auto;
		}
		.bw-error__message {
			font-size: 1.25rem;
			font-weight: 400;
			color: var(--bw-ink, #111);
			margin: 0 0 2rem;
			line-height: 1.5;
		}
		.bw-error__home {
			display: inline-block;
			background-color: var(--bw-blue, #00A3DD);
			color: #fff;
			text-decoration: none;
			font-weight: 600;
			padding: .75rem 1.75rem;
			border-radius: .4rem;
		}
		.bw-error__home:hover {
			background-color: var(--bw-blue-dark, #0089BA);
			color: #fff;
		}
		.bw-error__site {
			margin-top: 2rem;
			font-size: .85rem;
			color: var(--bw-muted, #5b6470);
		}
	</style>
</head>

<body>
	<div class="bw-error">
		<div class="bw-error__inner">
			<p class="bw-error__republic">Republic of Botswana</p>
			<h1 class="bw-error__code">@yield('code')</h1>
			<hr class="bw-divider">
			<p class="bw-error__message">@yield('message')</p>
			<a href="{{ url()->previous() }}" class="bw-error__home">Return to previous page</a>
			<p class="bw-error__site">{{ $settings->site_name }}</p>
		</div>
	</div>
</body>
</html>
