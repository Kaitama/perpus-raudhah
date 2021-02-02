@extends('layouts.dashboard')

@section('content')
<div class="ui segments">
	<div class="ui segment">
		<h4>Data Katalog</h4>
	</div>
	<div class="ui segment">
		@livewire('catalogs.index')
	</div>
</div>
@endsection
