@extends('layouts.dashboard')

@section('content')
<div class="ui segments">
	<div class="ui segment">
		<h4>Daftar Pegawai</h4>
	</div>
	<div class="ui segment">
		@livewire('staffs.index')
	</div>
</div>
@endsection