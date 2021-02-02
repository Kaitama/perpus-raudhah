<div>
	
	<div class="ui two column stackable grid">
		<div class="six wide column">
			<div class="ui icon input fluid">
				<input type="text" placeholder="Cari santri..." wire:model.debounce.500ms="search" autofocus>
				<i class="search icon"></i>
			</div>
		</div>
		<div class="ten wide column right aligned">
			<div wire:ignore class="ui input">
				<select wire:model="gen" class="ui dropdown">
					<option value="1">Semua Santri</option>
					<option value="L">Pria</option>
					<option value="P">Wanita</option>
				</select>
			</div>
			
		</div>
	</div>
	
	<table class="ui selectable celled table">
		<thead>
			<tr>
				<th class="collapsing">#</th>
				<th>Data Santri</th>
				<th>Kelas/Asrama</th>
				<th class="collapsing">Action</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($students as $k => $s)
			<tr>
				<td class="right aligned">{{$students->firstItem() + $k}}</td>
				<td>
					{{--  --}}
					@php $p = 'male.jpg'; if($s->gender == 'P') $p = 'female.jpg'; @endphp
					<div class="ui list">
						<div class="item">
							<img class="ui avatar image" src="https://sisfo.raudhah.ac.id/assets/img/student/{{$s->photo ?? $p}}">
							<div class="content">
								<a wire:click.prevent="show({{$s->id}})" class="header">{{strtoupper($s->name)}}</a>
								<div class="description">{{$s->stambuk}}</div>
							</div>
						</div>
					</div>
					{{--  --}}
				</td>
				<td>
					Kelas {{$s->classroom->name ?? '-'}}, Asrama {{$s->dormroom->name ?? '-'}}
				</td>
				<td>
					<div wire:click="show({{$s->id}})" class="ui labeled icon mini button teal"><i class="history icon"></i> History</div>
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
	
	
	<div class="ui grid">
		<div class="column center aligned">
			{{$students->links('vendor.livewire.semantic')}}
		</div>
	</div>
	
	
	{{-- modal details --}}
	<div id="modalDetails" class="ui modal">
		<div class="header">
			Lending History
		</div>
		<div class="scrolling content">
			<div class="description">
				@if($student)
				<div class="ui basic segment grid">
					<div class="five wide column">
						@php $ph = 'male.jpg'; if($student->gender == 'P') $ph = 'female.jpg'; @endphp
						<img src="https://sisfo.raudhah.ac.id/assets/img/student/{{$student->photo ?? $ph}}" class="ui image fluid">
					</div>
					<div class="eleven wide column">
						<div class="ui list">
							<div class="item">
								<div class="description">Stambuk</div>
								<div class="header">{{$student->stambuk}}</div>
							</div>
							<div class="item">
								<div class="description">Nama Lengkap</div>
								<div class="header">{{$student->name}}</div>
							</div>
							<div class="item">
								<div class="description">Kelas</div>
								<div class="header">{{$student->classroom ? $student->classroom->name : '-'}}</div>
							</div>
							<div class="item">
								<div class="description">Asrama</div>
								<div class="header">{{$student->dormroom ? $student->dormroom->name : '-'}}</div>
							</div>
							<div class="item">
								<div class="description">Tempat / Tanggal Lahir</div>
								<div class="header">{{$student->birthplace ?? '-'}} / {{$student->birthdate->isoFormat('LL')}}</div>
							</div>
							<div class="item">
								<div class="description">Jenis Kelamin</div>
								<div class="header">{{$student->gender == 'L' ? 'Laki-laki' : 'Perempuan'}}</div>
							</div>
							<div class="item">
								<div class="description">Status</div>
								<div class="header">{{$student->status == 1 ? 'Aktif' : 'Nonaktif'}}</div>
							</div>
						</div>
					</div>
				</div>
				
				<div class="ui basic segment">
					@if($student->lendings->count() > 0)
					<table class="ui table">
						<thead>
							<tr>
								<th>#</th>
								<th>Judul Buku</th>
								<th>Tanggal Pinjam</th>
								<th class="collapsing">Tanggal Kembali</th>
							</tr>
						</thead>
						<tbody>
							@php $i = 1 @endphp
							@foreach ($student->lendings->sortByDesc('lended_at') as $len)
							<tr>
								<td class="collapsing">{{$i++}}</td>
								<td>
									<h4 class="ui header">
										{{$len->bookdetail->book->title}}
										<div class="sub header">{{$len->bookdetail->barcode}}</div>
									</h4>
								</td>
								<td class="collapsing">
									<h4 class="ui header">
										{{$len->lended_at->isoFormat('LL')}}
										<div class="sub header">{{$len->lended_at->isoFormat('H:mm')}} WIB</div>
									</h4>
								</td>
								<td class="collapsing">
									<h4 class="ui header">
										@if ($len->bookdetail->status == 3)
										Hilang
										<div class="sub header">Denda: Rp. {{number_format($len->bookdetail->book->price, 0, ',', '.')}}</div>
										@else
										@if($len->returned_at) {{$len->returned_at->isoFormat('LL')}} 
										<div class="sub header">{{$len->returned_at->isoFormat('H:mm')}} WIB</div>
										@else {{'-'}} @endif
										@endif
									</h4>
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
					@else
					<div class="ui message">{{$student->name}} belum pernah meminjam buku.</div>
					@endif
				</div>
				@endif
			</div>
		</div>
		<div class="actions">
			@if($student)
			<a target="_blank" href="{{route('students.libpass', $student->id ?? '')}}" class="ui labeled icon teal button left floated {{$student->lendings->where('returned_at', null)->count() > 0 ? 'disabled' : ''}}">
				<i class="print icon"></i> Surat Bebas Perpus
			</a>
			@endif
			<div class="ui black deny button">
				Close
			</div>
		</div>
	</div>
	
</div>
