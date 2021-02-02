<div>
	@include('components.flashmessage')
	<div class="ui stackable grid">
		{{-- left --}}
		<div class="six wide column">
			<div class="ui big icon fluid input">
				<input wire:keydown.enter="searchUser" wire:model="uid" type="text" placeholder="NIK / Stambuk" id="memberInput" autofocus>
				<i class="user icon"></i>
			</div>
			<div class="ui divider"></div>
			{{-- profile --}}
			@if ($lender)
			@if ($ismember)
			{{-- member --}}
			<div class="ui two column grid">
				<div class="six wide column">
					<img class="ui image" src="{{asset('img/members')}}/{{$lender->photo ?? 'nopic.png'}}">
				</div>
				<div class="ten wide column">
					<div class="ui list">
						<div class="item">
							<div class="content">
								<div class="description">NIK</div>
								<div class="header">{{$lender->nik}}</div>
							</div>
						</div>
						<div class="item">
							<div class="content">
								<div class="description">Nama Lengkap</div>
								<div class="header">{{$lender->name}}</div>
							</div>
						</div>
						<div class="item">
							<div class="content">
								<div class="description">Telepon</div>
								<div class="header">{{$lender->phone}}</div>
							</div>
						</div>
						<div class="item">
							<div class="content">
								<div class="description">Alamat</div>
								<div class="header">{{$lender->address}}</div>
							</div>
						</div>
						<div class="item">
							<div class="content">
								<div class="description">Status</div>
								<div class="header">
									@switch($lender->status)
									@case(1)
									Guru Raudhah
									@break
									@case(2)
									Pegawai Raudhah
									@break
									@default
									Lainnya
									@endswitch
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			@else
			{{-- student --}}
			<div class="ui two column grid">
				<div class="six wide column">
					@php $p = 'male.jpg'; if($lender->gender == 'P') $p = 'female.jpg'; @endphp
					<img class="ui image" src="https://sisfo.raudhah.ac.id/assets/img/student/{{$s->photo ?? $p}}">
				</div>
				<div class="ten wide column">
					<div class="ui list">
						<div class="item">
							<div class="content">
								<div class="description">Stambuk</div>
								<div class="header">{{$lender->stambuk}}</div>
							</div>
						</div>
						<div class="item">
							<div class="content">
								<div class="description">Nama Lengkap</div>
								<div class="header">{{$lender->name}}</div>
							</div>
						</div>
						<div class="item">
							<div class="content">
								<div class="description">Kelas</div>
								<div class="header">{{$lender->classroom->name}}</div>
							</div>
						</div>
						<div class="item">
							<div class="content">
								<div class="description">Asrama</div>
								<div class="header">{{$lender->dormroom->name ?? '-'}}</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			@endif
			@endif
			
		</div>
		{{-- right --}}
		<div class="ten wide column">
			<div class="ui big icon fluid input @if($lended == 0) disabled @endif">
				<input wire:keydown.enter="addBooks" wire:model="bid" type="text" placeholder="No. Induk Buku" id="bookInput">
				<i class="book icon"></i>
			</div>
			<div class="ui divider"></div>
			
			@if($lender)
			@if($lended == 0)
			<div class="ui message">
				{{$ismember ? 'Member' : 'Santri'}} sedang tidak meminjam buku apapun.
			</div>
			@else
			{{-- list lended books --}}
			<table class="ui table">
				<thead>
					<tr>
						<th>#</th>
						<th>Buku yang Dipinjam</th>
						<th>Kondisi</th>
						<th class="collapsing">Tgl. Pinjam</th>
						<th class="collapsing">Option</th>
					</tr>
				</thead>
				<tbody>
					@php $n = 1 @endphp
					@foreach ($lender->lendings->where('returned_at', null) as $len)
					<tr>
						<td class="collapsing">{{$n++}}</td>
						<td>
							<h4 class="ui header">
								{{$len->bookdetail->book->title ?? ''}}
								<div class="sub header">{{$len->bookdetail->barcode ?? ''}}</div>
							</h4>
						</td>
						<td class="collapsing">
							{{$len->bookdetail->status == 1 ? 'Baik' : 'Rusak'}}
						</td>
						<td class="collapsing">
							<h4 class="ui header">
								{{$len->lended_at->isoFormat('LL')}}
								<div class="sub header">{{$len->lended_at->isoFormat('H:mm')}} WIB</div>
							</h4>
						</td>
						<td>
							<div wire:click="reportMissing({{$len->bookdetail}})" class="ui negative icon button" data-tooltip="Laporkan hilang" data-inverted="">
								<i class="exclamation icon"></i>
							</div>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
			@endif
			@endif
		</div>
		
		
		
	</div>
	
	@if($books->isNotEmpty())
	<div class="ui grid">
		<div class="column">
			<table class="ui black selectable table">
				<thead>
					<tr>
						<th>#</th>
						<th>Judul Buku</th>
						<th>Tgl. Pinjam</th>
						<th>Tgl. Kembali</th>
						<th>Denda</th>
					</tr>
				</thead>
				<tbody>
					@php $n = 1; $t = 0; @endphp
					@foreach ($books as $k => $b)
					<tr>
						<td class="collapsing">{{$n++}}</td>
						<td>
							<h4 class="ui header">
								{{$b['book']['title']}}
								<div class="sub header">{{$b['barcode']}}</div>
							</h4>
						</td>
						<td class="collapsing">
							<h4 class="ui header">
								{{$lender->lendings->where('bookdetail_id', $b['id'])->first()->lended_at->isoFormat('LL')}}
								<div class="sub header">{{$lender->lendings->where('bookdetail_id', $b['id'])->first()->lended_at->isoFormat('H:mm')}} WIB</div>
							</h4>
						</td>
						<td class="collapsing">
							<h4 class="ui header">
								{{$returning_date->isoFormat('LL')}}
								<div class="sub header">{{$returning_date->isoFormat('H:mm')}} WIB</div>
							</h4>
						</td>
						<td class="two wide">
							<h4 class="ui header">
								Rp. <span style="float: right!important">{{number_format($b['fines'], 0, ',', '.')}}</span>
								<div class="sub header">
									@switch($b['desc'])
											@case(1)
													Ok
													@break
											@case(2)
													Terlambat
													@break
											@default
													Hilang
									@endswitch
								</div>
							</h4>
							@php $t += $b['fines'] @endphp
						</td>
					</tr>
					@endforeach
				</tbody>
				<tfoot>
					<tr>
						<th colspan="4">Total Denda</th>
						<th>
							<div class="ui list">
								<div class="item">
									<div class="content">
										<div class="right floated">{{number_format($t, 0, ',', '.')}}</div>
										Rp.
									</div>
								</div>
							</div>	
						</th>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>

	<div class="ui grid">
		<div class="right aligned column">
			<div class="ui black button" wire:click="resetAll">
				Reset
			</div>
			<div class="ui positive labeled icon button" wire:click="store">
				<i class="checkmark icon"></i> Process
			</div>
		</div>
	</div>

	@endif
	
	
	
	{{-- modal alert --}}
	<div id="modalAlert" class="ui tiny modal">
		<div class="header">
			Failed!
		</div>
		<div class="content">
			<div class="description">
				<div class="ui negative icon message">
					<i class="attention icon"></i>
					<div class="content">
						<p>{!! $alertmessage !!}</p>
					</div>
				</div>
			</div>
		</div>
		<div class="actions">
			<div class="ui black deny button" wire:click="$emit('inputBookFocus')">
				OK
			</div>
		</div>
	</div>
	{{-- modal success --}}
	<div id="modalSuccess" class="ui tiny modal">
		<div class="header">
			Success!
		</div>
		<div class="content">
			<div class="description">
				<div class="ui positive icon message">
					<i class="checkmark icon"></i>
					<div class="content">
						<p>{!! $alertmessage !!}</p>
					</div>
				</div>
			</div>
		</div>
		<div class="actions">
			<div class="ui black deny button" wire:click="$emit('inputMemberFocus')">
				OK
			</div>
		</div>
	</div>
	{{-- modal confirm --}}
	<div id="modalConfirm" class="ui tiny modal">
		@if($confirm)
		<div class="header">
			Check Book Status
		</div>
		<div class="content">
			<h4 class="ui header">
				<div class="sub header">Judul Buku</div>
				{{$confirm->bookdetail->book->title}}
			</h4>
			<div class="ui horizontal divider">&bull;</div>
			<div class="description">
				<div wire:ignore class="ui form">
					<div class="field">
						<label>Kondisi Buku yang Dikembalikan</label>
						<select wire:model="condition" wire:change="editBookStatus({{$confirm->bookdetail->id}})" class="ui dropdown">
							<option value="1">Baik</option>
							<option value="2">Rusak</option>
						</select>
					</div>
				</div>
			</div>
		</div>
		<div class="actions">
			<div class="ui black deny button" wire:click="$emit('inputBookFocus')">
				Cancel
			</div>
			<div class="ui positive labeled icon button" wire:click="addRbooks">
				<i class="checkmark icon"></i>
				OK
			</div>
		</div>
		@endif
	</div>
	
</div>
