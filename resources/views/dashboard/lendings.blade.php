@extends('layouts.dashboard')

@section('content')
<div class="ui segments">
	<div class="ui segment">
		<h4>Peminjaman Buku</h4>
	</div>
	<div class="ui segment">
		@livewire('lendings.index')
	</div>
</div>
@endsection

@push('scripts')
window.livewire.on('inputBookFocus', () => {
	$('#bookInput').focus();
});
window.livewire.on('inputMemberFocus', () => {
	$('#memberInput').focus();
});
window.livewire.on('showModalAlert', () => {
	$('#modalAlert').modal('show');
});
window.livewire.on('showModalSuccess', () => {
	$('#modalSuccess').modal('show');
});
@endpush