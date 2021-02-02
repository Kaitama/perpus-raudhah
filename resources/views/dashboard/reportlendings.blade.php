@extends('layouts.dashboard')

@section('content')
<div class="ui segments">
	<div class="ui segment">
		<h4>Laporan Peminjaman</h4>
	</div>
	<div class="ui segment">
		@livewire('report.lendings.index')
	</div>
</div>
@endsection
