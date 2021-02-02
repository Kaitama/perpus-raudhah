@extends('layouts.dashboard')

@section('content')
<div class="ui segments">
	<div class="ui segment">
		<h4>Data Member</h4>
	</div>
	<div class="ui segment">
		<livewire:members.read />
	</div>
</div>
@endsection
