@extends('layouts.dashboard')

@section('content')
<div class="ui segments">
	<div class="ui segment">
		<h4>Data Buku</h4>
	</div>
	<div class="ui segment">
		<livewire:books.index />
	</div>
</div>
@endsection

@push('scripts')
window.livewire.on('showModalUpload', () => {
	$('#modalUpload').modal('show');
});
$("input:text, #attach").click(function() {
	$(this).parent().find("input:file").click();
});
$('input:file', '.ui.action.input')
.on('change', function(e) {
	var name = e.target.files[0].name;
	$('input:text', $(e.target).parent()).val(name);
});
@endpush