<div>
	@include('components.flashmessage')
	<div class="ui three column stackable grid">
		<div class="column">
			<div class="ui icon input fluid">
				<input type="text" placeholder="Cari katalog..." wire:model.debounce.500ms="search" autofocus>
				<i class="search icon"></i>
			</div>
		</div>
		<div class="column computer only"></div>
		<div class="column right aligned">
			@can('c perpus')
			<div wire:click="create" class="ui positive labeled icon button">
				<i class="plus icon"></i> Add New Catalog
			</div>
			@endcan
		</div>
	</div>
	
	@if ($catalogs->isEmpty())
	<div class="ui icon message">
		<i class="info icon"></i>
		<div class="content">
			<div class="header">Record empty!</div>
			<p>Data katalog masih kosong.</p>
		</div>
	</div>
	@else
	<table class="ui selectable table">
		<thead>
			<tr>
				<th>#</th>
				<th>Katalog</th>
				<th>Jumlah Buku</th>
				<th>Options</th>
			</tr>
		</thead>
		<tbody>
			@foreach($catalogs as $key => $cat)
			<tr>
				<td class="collapsing">{{$catalogs->firstItem() + $key}}</td>
				<td>
					<h5 class="ui header">
						<div class="content">
							<a wire:click.prevent="show({{$cat->id}})" href="#">{{$cat->name}}</a>
							<div class="sub header">
								DDC: {{$cat->catno}}
							</div>
						</div>
					</h5>
				</td>
				<td>
					<h5 class="ui header">
						<div class="content">
							{{$cat->books->count()}} Judul
							<div class="sub header">
								{{$cat->details->where('status', '<', 3)->count()}} Eksemplar
							</div>
						</div>
					</h5>
				</td>
				<td class="collapsing">
					<div class="ui basic icon buttons">
						<div class="ui button" data-tooltip="Detail" data-inverted="" wire:click="show({{$cat->id}})">
							<i class="eye icon"></i>
						</div>
						@can('u perpus')
						<div class="ui button" data-tooltip="Edit" data-inverted="" wire:click="edit({{$cat->id}})">
							<i class="edit icon"></i>
						</div>
						@endcan
						@can('d perpus')
						<div class="ui button" data-tooltip="Delete" data-inverted="" wire:click="confirmDelete({{$cat->id}})">
							<i class="trash icon"></i>
						</div>
						@endcan
					</div>
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
	@endif
	
	<div class="ui grid">
		<div class="column center aligned">
			{{$catalogs->links('vendor.livewire.semantic')}}
		</div>
	</div>
	
	@can('c perpus')
	{{-- modal create --}}
	<div wire:ignore.self id="modalCreate" class="ui modal">
		<div class="header">
			Add New Catalog
		</div>
		<div class="content">
			<div class="description">
				<div class="ui form error">
					{{-- form --}}
					<div class="fields">
						<div class="four wide field @error('catalog_number') error @enderror required">
							<label>Nomor Katalog</label>
							<input type="text" wire:model="catalog_number">
							@error('catalog_number') <div class="ui pointing red basic label">{{$message}}</div> @enderror
						</div>
						<div class="twelve wide field @error('catalog_name') error @enderror required">
							<label>Nama Katalog</label>
							<input type="text" wire:model="catalog_name">
							@error('catalog_name') <div class="ui pointing red basic label">{{$message}}</div> @enderror
						</div>
					</div>
					<div class="field">
						<label>Keterangan</label>
						<textarea rows="3" wire:model="description"></textarea>
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
	@endcan
	
	@can('u perpus')
	{{-- modal edit --}}
	<div wire:ignore.self id="modalEdit" class="ui modal">
		<div class="header">
			Edit Existing Catalog
		</div>
		<div class="content">
			<div class="description">
				<div class="ui form error">
					{{-- form --}}
					<div class="fields">
						<div class="four wide field @error('catalog_number') error @enderror required">
							<label>Nomor Katalog</label>
							<input type="text" wire:model="catalog_number">
							@error('catalog_number') <div class="ui pointing red basic label">{{$message}}</div> @enderror
						</div>
						<div class="twelve wide field @error('catalog_name') error @enderror required">
							<label>Nama Katalog</label>
							<input type="text" wire:model="catalog_name">
							@error('catalog_name') <div class="ui pointing red basic label">{{$message}}</div> @enderror
						</div>
					</div>
					<div class="field">
						<label>Keterangan</label>
						<textarea rows="3" wire:model="description"></textarea>
					</div>
					{{--  --}}
				</div>
			</div>
		</div>
		<div class="actions">
			<div class="ui black deny button">
				Cancel
			</div>
			<div class="ui positive right labeled icon button" wire:click="update">
				Save
				<i class="save icon"></i>
			</div>
		</div>
	</div>
	@endcan
	
	{{-- modal details --}}
	<div wire:ignore.self id="modalDetails" class="ui modal">
		<div class="header">
			Catalog Details
		</div>
		<div class="scrolling content">
			<div class="description">
				<div class="ui grid">
					@if($catalog)
					<div class="ui six wide column">
						<div class="ui large list">
							<div class="item">
								<div class="description">DDC</div>
								<div class="header">{{$catalog->catno}}</div>
							</div>
							<div class="item">
								<div class="description">Subjek</div>
								<div class="header">{{$catalog->name}}</div>
							</div>
							<div class="item">
								<div class="description">Keterangan</div>
								<div class="header">{{$catalog->description ?? '-'}}</div>
							</div>
						</div>
					</div>
					
					<div class="ui ten wide column">
						@if($catalog->books->count() > 0)
						<table class="ui selectable table">
							<thead>
								<tr>
									<th>#</th>
									<th>Judul Buku</th>
									<th class="collapsing">Eksemplar</th>
									<th class="collapsing">Dipinjamkan</th>
								</tr>
							</thead>
							<tbody>
								@php $i = 1 @endphp
								@foreach ($catalog->books as $b)
								<tr>
									<td class="collapsing">{{$i++}}</td>
									<td>{{$b->title}}</td>
									<td class="right aligned">{{$b->details->where('status', '<', 3)->count()}}</td>
									<td class="center aligned">
										<div class="circular mini ui {{$b->lendable ? 'positive' : 'negative'}} basic icon button" style="cursor: default">
											<i class="icon {{$b->lendable ? 'checkmark' : 'times'}}"></i>
										</div>
									</td>
								</tr>
								@endforeach
							</tbody>
							<tfoot>
								<tr>
									<th colspan="2">Total</th>
									<th class="right aligned">{{$catalog->details->where('status', '<', 3)->count()}}</th>
									<th></th>
								</tr>
							</tfoot>
						</table>
						@else
						<div class="ui message">
							Tidak ada buku yang terdaftar pada katalog ini.
						</div>
						@endif
					</div>
					@endif
				</div>
			</div>
		</div>
		<div class="actions">
			<div class="ui black deny button">
				Close
			</div>
		</div>
	</div>
	
	@can('d perpus')
	{{-- modal confirm delete --}}
	<div id="modalDelete" class="ui tiny modal">
		<div class="header">
			Confirm Delete
		</div>
		<div class="content">
			<div class="description">
				<div class="ui negative icon message">
					<i class="trash alternate outline icon"></i>
					<div class="content">
						<p>Anda yakin ingin menghapus katalog {{$idtodelete->catno ?? ''}} - {{$idtodelete->name ?? ''}}?</p>
					</div>
				</div>
			</div>
		</div>
		<div class="actions">
			<div class="ui black deny button">
				Cancel
			</div>
			<div class="ui positive right labeled icon button" wire:click="destroy">
				Yes, delete
				<i class="trash icon"></i>
			</div>
		</div>
	</div>
	@endcan
	
	
</div>
