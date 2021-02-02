<nav class="ui top fixed menu">
	<div class="left menu">
		<a href="#" class="sidebar-menu-toggler item" data-target="#sidebar">
			<i class="sidebar icon"></i>
		</a>
		<a href="{{url('/')}}" class="header item">
			RAUDHAH
		</a>
	</div>
	
	<div class="right menu">
		
		<div class="ui dropdown item">
			<img class="ui avatar image" src="{{Auth::user()->photo ? url('https://sisfo.raudhah.ac.id/assets/img/user/' . Auth::user()->photo) : url('https://sisfo.raudhah.ac.id/assets/img/user/nopic.png')}}">
			<span class="nav-username">{{ucfirst(Auth::user()->name)}}</span>
			<div class="menu">
				<a href="#" class="item changepassword">
					<i class="wrench icon"></i>
					Ubah Password
				</a>
				<a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="item">
					<i class="sign-out icon"></i>
					Logout
				</a>
				<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none">
					@csrf
			</form>
			</div>
		</div>
	</div>
</nav>