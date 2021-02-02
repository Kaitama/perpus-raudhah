<div>
	@include('components.flashmessage')
	<div class="ui two column stackable grid">
		<div class="column">
			<div class="ui form">
				<div class="four fields">
					<div class="field">
						<label>Dari Tanggal</label>
						<input type="text" wire:model.lazy="startDate">
					</div>
					<div class="field">
						<label>Sampai Tanggal</label>
						<input type="text" wire:model.lazy="endDate">
					</div>
					<div wire:ignore class="field">
						<label>Keanggotaan</label>
						<select class="ui dropdown" wire:model="membership">
							<option value="0">Semua</option>
							<option value="1">Santri</option>
							<option value="2">Member</option>
						</select>
					</div>
				</div>
			</div>
		</div>
		<div class="column right aligned">
			<a href="{{route('download.report.lendings', ['s' => $s, 'e' => $e, 'm' => $membership])}}" class="ui labeled icon positive button">
				<i class="download icon"></i> Download Report
			</a>
		</div>
	</div>
	
	@if ($lendings->isEmpty())
	<div class="ui icon message">
		<i class="info icon"></i>
		<div class="content">
			<div class="header">Record empty!</div>
			<p>Tidak ada data peminjaman yang sesuai dengan pencarian.</p>
		</div>
	</div>
	@else
	<table class="ui selectable table">
		<thead>
			<tr>
				<th>#</th>
				<th>Judul Buku</th>
				<th class="four wide">Peminjam</th>
				<th class="collapsing">Tanggal Pinjam</th>
				<th class="collapsing">Tanggal Kembali</th>
				<th>Denda</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($lendings as $lend)
			@php
			$cl = '';
			if($lend->lended_at->diffInDays($lend->returned_at) <= 5 && $lend->returned_at) $cl = 'positive';
			if($lend->dayfine) $cl = 'warning';
			if($lend->lostfine)  $cl = 'error';
			@endphp
			<tr class="{{$cl}}">
				<td class="collapsing">{{$no++}}</td>
				<td>
					<h5 class="ui header">
						{{$lend->bookdetail->book->title}}
						<div class="sub header">
							{{$lend->bookdetail->barcode}}
							<br>
							{{$lend->bookdetail->book->author}}, {{$lend->bookdetail->book->year}}
						</div>
					</h5>
				</td>
				<td>
					<h5 class="ui header">
						{{-- <i class="{{$lend->student ? 'graduation cap blue' : 'user teal'}} icon"></i> --}}
						<div class="content">
							{{$lend->student->name ?? $lend->member->name}}
							<div class="sub header">
								{{$lend->student->stambuk ?? $lend->member->nik}}
								<br>
								{{$lend->student ? 'Santri' : 'Member'}}
							</div>
						</div>
					</h5>
				</td>
				<td>
					<h5 class="ui header">
						{{$lend->lended_at->format('d/m/Y')}}
						<div class="sub header">
							{{$lend->lended_at->format('H:i')}} WIB 
							<br>
							{{$lend->users()->wherePivot('returning', false)->first()->name}}
						</div>
					</h5>
				</td>
				<td>
					<h5 class="ui header">
						@if ($lend->returned_at)
						{{$lend->returned_at->format('d/m/Y')}}
						<div class="sub header">
							{{$lend->returned_at->format('H:i')}} WIB
							<br>
							{{$lend->users()->wherePivot('returning', true)->first()->name}}
						</div>
						@else
						{{'-'}}
					</h5>
					
					@endif
				</td>
				<td class="two wide">
					<h5 class="ui header">
						@if ($lend->bookdetail->status == 3)
						Hilang
						@else
						@if ($lend->lended_at->diffInDays($lend->returned_at) > 5)
						Terlambat
						@else
						{{'-'}}
						@endif
						@endif
						@if($lend->bookdetail->status == 3 || $lend->lended_at->diffInDays($lend->returned_at) > 5)
						<div class="sub header">
							Rp. <span style="float: right!important">{{$lend->dayfine ? number_format($lend->dayfine, 0, ',', '.') : number_format($lend->lostfine, 0, ',', '.')}}</span>
						</div>
						@endif
					</h5>
				</span>
			</td>
		</tr>
		@endforeach
	</tbody>
	<tfoot>
		<tr>
			<th></th>
			<th colspan="4">
				<h4 class="ui header red">Total Denda</h4>
			</th>
			<th>
				<h4 class="ui header red">
					@php $sum = $lendings->sum('dayfine') + $lendings->sum('lostfine') @endphp
					Rp. <span style="float: right !important">{{number_format($sum, 0, ',' , '.')}}</span>
				</h4>
			</th>
		</tr>
	</tfoot>
</table>
@endif

</div>

{{--  --}}