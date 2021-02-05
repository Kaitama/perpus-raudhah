@php
$s = Request::segment(2);
@endphp
<div class="ui sidebar vertical menu sidebar-menu" id="sidebar">
	<div class="item">
		<img src="{{asset('img/app/logo.png')}}" class="ui small image centered" alt="Raudhah">
	</div>
	
	

	<a href="{{route('dashboard.index')}}" class="item{{$s == null ? ' active' : ''}}">
		<div>
			<i class="icon home grey"></i>
			Dashboard
		</div>
	</a>
	@can('r perpus')
	<a href="{{route('lendings.index')}}" class="item{{$s == 'lendings' ? ' active' : ''}}">
		<div>
			<i class="icon share square grey"></i>
			Peminjaman Buku
		</div>
	</a>
	<a href="{{route('returnings.index')}}" class="item{{$s == 'returnings' ? ' active' : ''}}">
		<div>
			<i class="icon reply grey"></i>
			Pengembalian Buku
		</div>
	</a>
	@endcan
	
	<div class="item">
		<div class="sidebar-header">
			Basis Data
		</div>
	</div>
	
	@can('m perpus')
	<a href="{{route('staffs.index')}}" class="item{{$s == 'staffs' ? ' active' : ''}}">
		<div>
			<i class="users icon grey"></i>
			Data Pegawai
		</div>
	</a>
	@endcan
	
	<a href="{{route('students.index')}}" class="item{{$s == 'students' ? ' active' : ''}}">
		<div>
			<i class="id card icon grey"></i>
			Data Santri
		</div>
	</a>
	<a href="{{route('members.index')}}" class="item{{$s == 'members' ? ' active' : ''}}">
		<div>
			<i class="id badge icon grey"></i>
			Data Member
		</div>
	</a>
	<a href="{{route('catalogs.index')}}" class="item{{$s == 'catalogs' ? ' active' : ''}}">
		<div>
			<i class="tag icon grey"></i>
			Data Katalog
		</div>
	</a>
	<a href="{{route('books.index')}}" class="item{{$s == 'books' ? ' active' : ''}}">
		<div>
			<i class="book icon grey"></i>
			Data Buku
		</div>
	</a>

	@can(['m perpus'])
	<div class="item">
		<div class="sidebar-header">
			Laporan
		</div>
	</div>
	<a href="{{route('report.lendings')}}" class="item{{$s == 'report' ? ' active' : ''}}">
		<div>
			<i class="retweet icon grey"></i>
			Laporan Peminjaman
		</div>
	</a>
	@endcan
	

	<div class="ui basic segment">
		<strong>&copy;{{date('Y')}} <a href="{{env('APP_URL')}}">{{env('APP_NAME')}}</a></strong><br> 
		<small><br>Crafted by</small><br> <span class="jariyah-text">JARIYAH</span> Digital Solution
	</div>
	<div class="ui basic segment"></div>
</div>