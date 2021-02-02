@extends('layouts.dashboard')

@section('content')
<div class="ui segments">
	<div class="ui segment">
		<h4>Pengembalian Buku</h4>
	</div>
	<div class="ui segment">
		@livewire('returnings.index')
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
window.livewire.on('showModalConfirm', () => {
	$('#modalConfirm').modal({
		autofocus: false,
	}).modal('show');
	$('.ui.dropdown').dropdown();
});
@endpush