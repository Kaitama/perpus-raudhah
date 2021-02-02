
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
	<title>Perpustakaan Pesantren Ar-Raudlatul Hasanah</title>
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
			<div class="right menu">
				@if (Route::has('login'))
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
				@endif
			</div>
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
					<div class="right item">
						@auth
						<a href="{{url('dashboard')}}" class="ui inverted button">Dashboard</a>
						<a href="{{route('logout')}}" class="ui inverted button" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
						@else
						<a href="{{route('login')}}" class="ui inverted button">Log in</a>
						@endauth
					</div>
					@endif
				</div>
			</div>
			@livewire('frontpage.index')
			
			<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none">
				@csrf
			</form>
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
						<a class="item" href="https://raudhah.ac.id">Website</a>
						<a class="item" href="https://raudhah.ac.id/category/berita">Berita Terbaru</a>
						<a class="item" href="http://stit-rh.ac.id/">STIT Ar-Raudlatul Hasanah</a>
						<a class="item" href="https://perpus.raudhah.ac.id/">Perpustakaan</a>
					</div>
				</div>
				<div class="three wide column">
					<h4 class="ui inverted header">Profil</h4>
					<div class="ui inverted link list">
						<a class="item" href="https://raudhah.ac.id/sejarah-pesantren">Sejarah Pesantren</a>
						<a class="item" href="https://raudhah.ac.id/ar-raudlatul-hasanah-2-lumut">Sejarah RH 2 Lumut</a>
						<a class="item" href="https://raudhah.ac.id/visi-misi">Visi & Misi</a>
						<a class="item" href="https://raudhah.ac.id/panca-jiwa">Panca Jiwa</a>
						<a class="item" href="https://raudhah.ac.id/motto-pesantren">Motto Pesantren</a>
						<a class="item" href="https://raudhah.ac.id/struktur-organisasi-2">Struktur Organisasi</a>
					</div>
				</div>
				<div class="seven wide column">
					<h4 class="ui inverted header">Pesantren Ar-Raudlatul Hasanah</h4>
					<p>
						Jl. Letjen. Jamin Ginting Km. 11 Paya Bundung/ Jl. Setia Budi Ujung Simpang Selayang, Medan, 20135 <br>
						<div class="ui basic label icon">
							<i class="phone icon"></i>0823 6266 4000
						</div>
						<div class="ui basic label icon">
							<i class="mail icon"></i>sekretarispusatraudhah@gmail.com
						</div>
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
