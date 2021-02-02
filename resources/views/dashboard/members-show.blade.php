@extends('layouts.dashboard')

@section('content')

<div class="ui segments">
	<div class="ui segment">
		<div class="ui breadcrumb">
			<h4>
				<a class="section" href="{{route('members.index')}}">Data Member</a>
				<div class="divider"> / </div>
				<div class="active section">Details</div>
			</h4>
		</div>
	</div>
	<div class="ui segment">
		<livewire:members.show :dataid="Route::current()->parameter('dataid')"/>
	</div>
</div>
@endsection

