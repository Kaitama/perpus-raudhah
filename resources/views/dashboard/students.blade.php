@extends('layouts.dashboard')

@section('content')
		<div class="ui segments">
			<div class="ui segment">
				<h4>Data Santri</h4>
			</div>
			<div class="ui segment">
				<livewire:students.read />
			</div>
		</div>
@endsection