<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">
	
	<title>{{ config('app.name', 'Laravel') }}</title>
	<!-- Styles -->
	<link rel="stylesheet" href="{{asset('semantic/semantic.css')}}">
	<link href="{{ asset('css/style.css') }}" rel="stylesheet">
	@livewireStyles
	
	<!-- Fonts -->
	<link
	rel="stylesheet"
	href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css"
	integrity="sha256-+N4/V/SbAFiW1MPBCXnfnP9QSN3+Keu+NlB+0ev/YKQ="
	crossorigin="anonymous"
	/>
	
	<style>
		body {
			background-color: whitesmoke !important;
		}
		.main-content {
			padding-top: 28px !important;
			padding-left: 14px !important;
			padding-right: 14px !important;
		}
		.site-brand-text {
			font-weight: 700 !important;
			font-size: 1.2rem !important;
			padding-top: 4px !important;
		}
		.nav-username {
			padding-left: 4px !important;
		}
		.item .sidebar-header {
			color: grey !important;
			padding-top: 10px !important;
			font-size: 0.8rem !important;
			letter-spacing: 0.05rem !important;
			font-weight: bold !important;
			text-transform: uppercase !important;
		}
		
		.jariyah-text {
			color: lightseagreen !important;
			font-weight: bold !important;
		}
		
		.profile.metadata {
			margin-left: 0 !important;
		}
		
		.rtl {
			text-align: right !important;
		}
	</style>
	
</head>
<body>
	
	{{-- sidebar --}}
	@include('components.topnav')
	@include('components.sidebar')
	
	{{-- main content --}}
	<div class="pusher">
		<div class="main-content">
			@yield('content')
		</div>
	</div>
	
	
	
	<!-- Scripts -->
	{{-- <script src="{{ asset('js/app.js') }}" defer></script> --}}
	<script src="{{asset('js/jquery.min.js')}}"></script>
	<script src="{{asset('semantic/semantic.js')}}"></script>
	<script src="{{asset('semantic/tablesort.js')}}"></script>
	<script src="{{asset('js/script.js')}}"></script>
	@livewireScripts
	<script>
		$(document).ready(function(){
			$('table').tablesort();
			window.livewire.on('showModalCreate', () => {
				$('#modalCreate').modal('show');
				$('.ui.dropdown').dropdown();
			});
			window.livewire.on('showModalDelete', () => {
				$('#modalDelete').modal('show');
			});
			window.livewire.on('showModalDetails', () => {
				$('#modalDetails').modal({
					autofocus: false,
				}).modal('show');
				$('.ui.dropdown').dropdown();
			});
			window.livewire.on('showModalEdit', () => {
				$('#modalEdit').modal('show');
				$('.ui.dropdown').dropdown();
			});
			window.livewire.on('refreshModal', () => {
				$('.ui.modal').modal('hide all');
				$('.ui.dropdown').dropdown();
			});
			@stack('scripts')
		});
	</script>
</body>
</html>