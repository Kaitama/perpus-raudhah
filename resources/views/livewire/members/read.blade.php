<div>
	@include('components.flashmessage')
	<div class="ui three column stackable grid">
		<div class="column">
			<div class="ui icon input fluid">
				<input type="text" placeholder="Cari member..." wire:model.debounce.500ms="search" autofocus>
				@if ($searching)
				<i class="inverted circular times link icon" wire:click="resetSearch"></i>
				@endif
			</div>
		</div>
		<div class="column computer only"></div>
		<div class="column right aligned">
			<div wire:click="create" class="ui positive labeled icon button">
				<i class="plus icon"></i> Add Member
			</div>
		</div>
	</div>
	
	<div class="ui stackable three column grid">
		@foreach ($members as $m)
		
		{{-- member list --}}
		<div class="column">
			<div class="ui fluid card">
				<div class="content">
					<img class="right floated mini ui image" src="{{asset('img/members')}}/{{$m->photo ?? 'nopic.png'}}">
					<div class="header">
						{{$m->name}}
					</div>
					<div class="meta">
						@switch($m->status)
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
					<div class="ui divider"></div>
					
					<div class="description">
						@php $last_lending = $m->lendings->sortByDesc('lended_at')->first()->lended_at ?? null; @endphp
						@if($last_lending) Peminjaman terakhir tanggal {{$last_lending->isoFormat('LL')}}.
						@else Member belum pernah meminjam buku. @endif
						<br>
						<div class="meta">Anggota sejak {{$m->created_at->diffForHumans()}}.</div>	
					</div>
				</div>
				<div class="extra content">
					<div class="ui three tiny buttons">
						<div class="ui basic green button" wire:click="show({{$m->id}})">History</div>
						<div class="ui basic grey button showModalEdit" wire:click="edit({{$m->id}})">Edit</div>
						<div class="ui basic red button" wire:click="confirmDelete({{$m->id}})">Delete</div>
					</div>
				</div>
			</div>
		</div>
		{{--  --}}
		@endforeach
	</div>
	
	@if($members->isEmpty())
	<div class="ui message">Data tidak ditemukan.</div>
	@endif
	
	<div class="ui grid">
		<div class="column center aligned">
			{{$members->links('vendor.livewire.semantic')}}
		</div>
	</div>
	
	
	
	{{-- modal confirm delete --}}
	<div id="modalDelete" class="ui tiny modal">
		<div class="header">
			Confirm Delete
		</div>
		<div class="content">
			<div id="confirmMessage" class="description">
				<div class="ui negative icon message">
					<i class="trash alternate outline icon"></i>
					<div class="content">
						<p>Anda yakin ingin menghapus data member {{$name ?? ''}}?</p>
					</div>
				</div>
				
			</div>
		</div>
		<div class="actions">
			<div class="ui black deny button">
				Cancel
			</div>
			<div class="ui positive right labeled icon button" wire:click="destroy({{$idToDelete}})">
				Yes, delete
				<i class="trash icon"></i>
			</div>
		</div>
	</div>
	
	{{-- modal create --}}
	<div wire:ignore.self id="modalCreate" class="ui modal">
		<div class="header">
			Add New Member
		</div>
		<div class="content">
			<div class="description">
				<div class="ui form error">
					{{-- form --}}
					<div class="two fields">
						<div class="field @error('nik') error @enderror required">
							<label>NIK</label>
							<input type="text" wire:model="nik">
							@error('nik') <div class="ui pointing red basic label">{{$message}}</div> @enderror
						</div>
						<div class="field @error('name') error @enderror required">
							<label>Nama Lengkap</label>
							<input type="text" wire:model="name">
							@error('name') <div class="ui pointing red basic label">{{$message}}</div> @enderror
						</div>
					</div>
					<div class="two fields">
						<div class="field @error('email') error @enderror required">
							<label>Email</label>
							<input type="email" wire:model.lazy="email">
							@error('email') <div class="ui pointing red basic label">{{$message}}</div> @enderror
						</div>
						<div class="field @error('phone') error @enderror required">
							<label>Telepon</label>
							<input type="text" wire:model.lazy="phone">
							@error('phone') <div class="ui pointing red basic label">{{$message}}</div> @enderror
						</div>
					</div>
					<div class="two fields">
						<div class="field @error('birthplace') error @enderror required">
							<label>Tempat Lahir</label>
							<input type="text" wire:model.lazy="birthplace">
							@error('birthplace') <div class="ui pointing red basic label">{{$message}}</div> @enderror
						</div>
						<div class="field @error('birthdate') error @enderror required">
							<label>Tanggal Lahir</label>
							<input type="text" wire:model.lazy="birthdate">
							@error('birthdate') <div class="ui pointing red basic label">{{$message}}</div> @enderror
						</div>
					</div>
					<div class="two fields">
						<div wire:ignore class="field">
							<label>Jenis Kelamin</label>
							<select wire:model="gender" id="selGender" class="ui dropdown">
								<option value="1">Laki-laki</option>
								<option value="2">Perempuan</option>
							</select>
						</div>
						<div wire:ignore class="field">
							<label>Status</label>
							<select wire:model="status" class="ui dropdown">
								@foreach ($statuses as $k => $val)
								<option value="{{$k}}">{{$val}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="field @error('address') error @enderror required">
						<label>Alamat</label>
						<textarea rows="3" wire:model="address"></textarea>
						@error('address') <div class="ui pointing red basic label">{{$message}}</div> @enderror
					</div>
					{{--  --}}
				</div>
			</div>
		</div>
		<div class="actions">
			<div class="ui black deny button">
				Cancel
			</div>
			<div class="ui positive right labeled icon button" wire:click="store()">
				Save
				<i class="save icon"></i>
			</div>
		</div>
	</div>
	
	{{-- modal edit --}}
	<div wire:ignore.self id="modalEdit" class="ui modal">
		<div class="header">
			Edit Member
		</div>
		<div class="content">
			<div class="description">
				<div wire:loading.delay wire:target.edit>
					<div class="ui segment">
						<p>Loading...</p>
						<div class="ui active dimmer">
							<div class="ui loader"></div>
						</div>
					</div>
				</div>
				<div class="ui form error">
					{{-- form --}}
					<input type="hidden" wire:model="dataid">
					<div class="two fields">
						<div class="field @error('nik') error @enderror required">
							<label>NIK</label>
							<input type="text" wire:model="nik" value="{{$nik}}">
							@error('nik') <div class="ui pointing red basic label">{{$message}}</div> @enderror
						</div>
						<div class="field @error('name') error @enderror required">
							<label>Nama Lengkap</label>
							<input type="text" wire:model="name">
							@error('name') <div class="ui pointing red basic label">{{$message}}</div> @enderror
						</div>
					</div>
					<div class="two fields">
						<div class="field @error('email') error @enderror required">
							<label>Email</label>
							<input type="email" wire:model.lazy="email">
							@error('email') <div class="ui pointing red basic label">{{$message}}</div> @enderror
						</div>
						<div class="field @error('phone') error @enderror required">
							<label>Telepon</label>
							<input type="text" wire:model.lazy="phone">
							@error('phone') <div class="ui pointing red basic label">{{$message}}</div> @enderror
						</div>
					</div>
					<div class="two fields">
						<div class="field @error('birthplace') error @enderror required">
							<label>Tempat Lahir</label>
							<input type="text" wire:model.lazy="birthplace">
							@error('birthplace') <div class="ui pointing red basic label">{{$message}}</div> @enderror
						</div>
						<div class="field @error('birthdate') error @enderror required">
							<label>Tanggal Lahir</label>
							<input type="text" wire:model.lazy="birthdate">
							@error('birthdate') <div class="ui pointing red basic label">{{$message}}</div> @enderror
						</div>
					</div>
					<div class="two fields">
						<div wire:ignore class="field">
							<label>Jenis Kelamin</label>
							<select wire:model="gender" id="selGender" class="ui dropdown">
								<option value="1">Laki-laki</option>
								<option value="2">Perempuan</option>
							</select>
						</div>
						<div wire:ignore class="field">
							<label>Status</label>
							<select wire:model="status" class="ui dropdown">
								@foreach ($statuses as $k => $val)
								<option value="{{$k}}">{{$val}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="field @error('address') error @enderror required">
						<label>Alamat</label>
						<textarea rows="3" wire:model="address"></textarea>
						@error('address') <div class="ui pointing red basic label">{{$message}}</div> @enderror
					</div>
					{{--  --}}
				</div>
			</div>
		</div>
		<div class="actions">
			<div class="ui black deny button">
				Cancel
			</div>
			<div class="ui positive right labeled icon button" wire:click="update()">
				Update
				<i class="checkmark icon"></i>
			</div>
		</div>
	</div>
	
	{{-- modal details --}}
	<div id="modalDetails" class="ui modal">
		<div class="header">
			Lending History
		</div>
		<div class="scrolling content">
			<div class="description">
				@if($member)
				<div class="ui basic segment grid">
					<div class="five wide column">
						<img src="{{asset('img/members')}}/{{$member->photo ?? 'nopic.png'}}" class="ui image fluid">
					</div>
					<div class="eleven wide column">
						<div class="ui list">
							<div class="item">
								<div class="description">NIK</div>
								<div class="header">{{$member->nik}}</div>
							</div>
							<div class="item">
								<div class="description">Nama Lengkap</div>
								<div class="header">{{$member->name}}</div>
							</div>
							<div class="item">
								<div class="description">Telepon / Email</div>
								<div class="header">{{$member->phone ?? ''}} / {{$member->email ?? ''}}</div>
							</div>
							<div class="item">
								<div class="description">Alamat</div>
								<div class="header">{{$member->address ?? ''}}</div>
							</div>
							<div class="item">
								<div class="description">Tempat / Tanggal Lahir</div>
								<div class="header">{{$member->birthplace}} / {{$member->birthdate->isoFormat('LL')}}</div>
							</div>
							<div class="item">
								<div class="description">Jenis Kelamin</div>
								<div class="header">{{$member->gender ? 'Laki-laki' : 'Perempuan'}}</div>
							</div>
							<div class="item">
								<div class="description">Status</div>
								<div class="header">{{$statuses[$member->status]}}</div>
							</div>
						</div>
					</div>
				</div>
				
				<div class="ui basic segment">
					@if($member->lendings->count() > 0)
					<table class="ui table">
						<thead>
							<tr>
								<th>#</th>
								<th>Judul Buku</th>
								<th>Tanggal Pinjam</th>
								<th>Tanggal Kembali</th>
							</tr>
						</thead>
						<tbody>
							@php $i = 1 @endphp
							@foreach ($member->lendings->sortByDesc('lended_at') as $len)
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
										@if($len->returned_at) {{$len->returned_at->isoFormat('LL')}} 
										<div class="sub header">{{$len->returned_at->isoFormat('H:mm')}} WIB</div>
										@else {{'-'}} @endif
									</h4>
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
					@else
					<div class="ui message">{{$member->name}} belum pernah meminjam buku.</div>
					@endif
				</div>
				@endif
			</div>
		</div>
		<div class="actions">
			<div class="ui black deny button">
				Close
			</div>
		</div>
	</div>
	
	
</div>
