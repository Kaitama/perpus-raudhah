
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible" />
	<meta
	name="viewport"
	content="width=device-width, initial-scale=1, maximum-scale=2, user-scalable=no"
	/>
	<meta
	name="description"
	content="Perpustakaan Pesantren Ar-Raudlatul Hasanah."
	/>
	<meta name="keywords" content="Raudhah, Perpustakaan, Libary" />
	<meta name="author" content="PPType" />
	<meta name="theme-color" content="#ffffff" />
	<title>Homepage Template for Semantic-UI</title>
	<link
	rel="stylesheet"
	href="{{asset('semantic/semantic.css')}}"
	type="text/css"
	/>
	<link rel="stylesheet" href="{{asset('css/front.css')}}">
	@livewireStyles
</head>

<body id="root">
	<div class="ui large top fixed hidden menu">
		<div class="ui container">
			<a class="active item">Home</a>
			<a class="item">Validasi</a>
			@if (Route::has('login'))
			<div class="right menu">
				@auth
				<div class="item right">
					<a href="{{ url('/dashboard') }}" class="ui blue button">Dashboard</a>
				</div>
				<div class="item right">
					<a href="{{ route('logout') }}" class="ui button" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
				</div>
				@else
				<div class="item right">
					<a href="{{ route('login') }}" class="ui button">Log in</a>
				</div>
				@endauth
			</div>
			@endif
		</div>
	</div>
	<!--Sidebar Menu-->
	<div class="ui vertical inverted sidebar menu">
		<a class="active item">Home</a>
		<a class="item">Validasi</a>
		@if (Route::has('login'))
		@auth
		<a href="{{url('/dashboard')}}" class="item">Dashboard</a>
		<a href="{{route('logout')}}" class="item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
		@else
		<a href="{{route('login')}}" class="item">Login</a>
		@endauth
		@endif
	</div>
	<!--Page Contents-->
	<div class="pusher">
		<div class="ui inverted vertical masthead center aligned segment">
			<div class="ui container">
				<div class="ui large secondary inverted pointing menu">
					<a class="toc item">
						<i class="sidebar icon"></i>
					</a>
					<a class="active item">Home</a>
					<a class="item">Validasi</a>
					@if (Route::has('login'))
					@auth
					<div class="right item">
						<a href="{{url('dashboard')}}" class="ui inverted button">Dashboard</a>
						<a href="{{route('logout')}}" class="ui inverted button" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
					</div>
					@else
					<div class="right item">
						<a href="{{route('login')}}" class="ui inverted button">Log in</a>
					</div>
				</div>
				@endauth
				@endif
			</div>
			<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none">
				@csrf
			</form>
		</div>
		@livewire('frontpage.index')
	</div>
	
	<div class="ui vertical stripe segment">
		<div class="ui middle aligned stackable grid container">
			<div class="row">
				<div class="eight wide column">
					{{-- stats --}}
					@livewire('frontpage.statistics')
					{{-- /stats --}}
				</div>
				<div class="six wide right floated column">
					<img
					class="ui large rounded image"
					src="{{asset('img/app/logo.png')}}"
					/>
				</div>
			</div>
			
		</div>
	</div>
	
	<div class="ui inverted vertical footer segment">
		<div class="ui container">
			<div
			class="ui stackable inverted divided equal height stackable grid"
			>
			<div class="three wide column">
				<h4 class="ui inverted header">About</h4>
				<div class="ui inverted link list">
					<a class="item" href="#root">Sitemap</a>
					<a class="item" href="#root">Contact Us</a>
					<a class="item" href="#root">Religious Ceremonies</a>
					<a class="item" href="#root">Gazebo Plans</a>
				</div>
			</div>
			<div class="three wide column">
				<h4 class="ui inverted header">Services</h4>
				<div class="ui inverted link list">
					<a class="item" href="#root">Banana Pre-Order</a>
					<a class="item" href="#root">DNA FAQ</a>
					<a class="item" href="#root">How To Access</a>
					<a class="item" href="#root">Favorite X-Men</a>
				</div>
			</div>
			<div class="seven wide column">
				<h4 class="ui inverted header">Footer Header</h4>
				<p>
					Extra space for a call to action inside the footer that could
					help re-engage users.
				</p>
			</div>
		</div>
	</div>
</div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.3/semantic.min.js"></script>
@livewireScripts
<script>
	$(document).ready(function() {
		window.livewire.on('showModalDetails', () => {
			$('#modalDetails').modal({
				autofocus: false,
			}).modal('show');
			$('.ui.dropdown').dropdown();
		});
		// fix menu when passed
		$(".masthead").visibility({
			once: false,
			onBottomPassed: function() {
				$(".fixed.menu").transition("fade in");
			},
			onBottomPassedReverse: function() {
				$(".fixed.menu").transition("fade out");
			}
		});
		
		// create sidebar and attach to menu open
		$(".ui.sidebar").sidebar("attach events", ".toc.item");
	});
</script>
</body>
</html>
