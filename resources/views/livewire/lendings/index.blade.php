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
			<div class="ui big icon fluid input @if(!$lender || $lended >= 3) disabled @endif">
				<input wire:keydown.enter="addBooks" wire:model="bid" type="text" placeholder="No. Induk Buku" id="bookInput">
				<i class="book icon"></i>
			</div>
			<div class="ui divider"></div>
			@if($lended >= 3)
			<div class="ui negative message">Jumlah maksimum buku yang dapat dipinjam telah tercapai.</div>
			@endif
			
			{{-- list peminjaman buku --}}
			@if($books->isNotEmpty())
			<table class="ui table">
				<thead>
					<tr>
						<th>#</th>
						<th>Judul Buku</th>
						<th>Kondisi</th>
						<th class="collapsing">Option</th>
					</tr>
				</thead>
				<tbody>
					@php $n = 1 @endphp
					@foreach ($books as $k => $b)
					<tr>
						<td class="collapsing">{{$n++}}</td>
						<td>
							<h4 class="ui header">
								<div class="sub header">{{$b['barcode'] ?? ''}}</div>
								{{$b['book']['title'] ?? ''}}
							</h4>
						</td>
						<td class="collapsing">
							{{$b['status'] == 1 ? 'Baik' : 'Rusak'}}
						</td>
						<td class="center aligned">
							<div class="ui icon negative button" wire:click.prevent="remove({{$k}})">
								<i class="minus icon"></i>
							</div>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
			@endif
			
			{{-- list buku yang dipinjam --}}
			@if ($lender)
			@if($lender->lendings->where('returned_at', null)->count() > 0)
			<table class="ui table">
				<thead>
					<tr>
						<th>#</th>
						<th>Buku yang Dipinjam</th>
						<th>Kondisi</th>
						<th class="collapsing">Tgl. Pinjam</th>
					</tr>
				</thead>
				<tbody>
					@php $n = 1 @endphp
					@foreach ($lender->lendings->where('returned_at', null) as $len)
					<tr>
						<td class="collapsing">{{$n++}}</td>
						<td>
							<h4 class="ui header">
								<div class="sub header">{{$len->bookdetail->barcode ?? ''}}</div>
								{{$len->bookdetail->book->title ?? ''}}
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
					</tr>
					@endforeach
				</tbody>
			</table>
			@endif
			@endif
			
		</div>
		
	</div>
	
	@if($books->count() > 0)
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
	
</div>
