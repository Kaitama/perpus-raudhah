<div>
	@include('components.flashmessage')
	<div class="ui two column stackable grid">
		<div class="six wide column">
			<div class="ui icon input fluid">
				<input type="text" placeholder="Cari buku..." wire:model.debounce.500ms="search" autofocus>
				<i class="search icon"></i>
			</div>
		</div>
		<div class="ten wide column right aligned">
			<div wire:ignore class="ui input">
				<select wire:model="nocat" class="ui dropdown">
					<option value="1">Semua Buku</option>
					<option value="2">Dengan Katalog</option>
					<option value="3">Tanpa Katalog</option>
				</select>
			</div>
			@can('c perpus')
			<div class="ui dropdown">
				<div class="ui teal labeled icon button">
					<i class="chevron down icon"></i> Export/Import
				</div>
				<div class="menu">
					<a href="{{route('books.template')}}" class="item">
						<i class="file excel icon"></i> Download Template
					</a>
					<div class="divider"></div>
					<div class="item" wire:click="$emit('showModalUpload')">
						<i class="upload icon"></i> Import Excel
					</div>
					<a href="{{route('books.export')}}" class="item">
						<i class="download icon"></i> Export Excel
					</a>
					<div wire:click="downloadBarcode" class="item">
						<i class="barcode icon"></i> Download Barcode
					</div>
				</div>
			</div>
			<div wire:click="create" class="ui positive labeled icon button">
				<i class="plus icon"></i> Add New Books
			</div>
			@endcan
		</div>
	</div>
	
	@if ($books->isEmpty())
	<div class="ui icon message">
		<i class="info icon"></i>
		<div class="content">
			<div class="header">Record empty!</div>
			<p>Data buku masih kosong, silahkan tambah data buku melalui tombol <b>Add New Books</b> atau <b>Import Excel</b>.</p>
		</div>
	</div>
	@else
	<table class="ui selectable table">
		<thead>
			<tr>
				<th>#</th>
				<th>Data Buku</th>
				<th>Info Tambahan</th>
				<th>Sumber Buku</th>
				<th class="collapsing">Dipinjamkan</th>
				<th>Options</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($books as $key => $b)
			<tr>
				<td>{{$books->firstItem() + $key}}</td>
				<td>
					<h5 class="ui header">
						<div class="content">
							<a href="#" wire:click.prevent="show({{$b->id}})">{{$b->title}}</a>
							<div class="sub header">
								{{$b->author}}, {{$b->year}} <br>
								{{$b->publisher}}
							</div>
						</div>
					</h5>
				</td>
				<td>
					<h5 class="ui header">
						<div class="content">
							{{$b->details->count()}} Eksemplar @if($b->details->where('status', 3)->count() > 0)<span style="color: red">(-{{$b->details->where('status', 3)->count()}})</span> @endif
							@if ($b->catalog)
							<div class="sub header">
								Katalog: 
								{{$b->catalog->name ?? ''}}
								<br>
								DDC: {{$b->catalog->catno ?? '-'}}
								@endif
							</div>
						</div>
					</h5>
				</td>
				<td>
					<h5 class="ui header">
						<div class="content">
							{{$b->source ?? '-'}}
							<div class="sub header">
								{{$b->purchased_at->isoFormat('LL')}},
								@Rp. {{number_format($b->price, 0, ',', '.')}}
								<br>
								{{$b->description}}
							</div>
						</div>
					</h5>
				</td>
				
				<td class="collapsing center aligned">
					<div class="circular mini ui {{$b->lendable ? 'positive' : 'negative'}} basic icon button" style="cursor: default">
						<i class="icon {{$b->lendable ? 'checkmark' : 'times'}}"></i>
					</div>
				</td>
				<td class="collapsing center aligned">
					<div class="ui basic icon buttons">
						<div class="ui button" data-tooltip="Detail" data-inverted="" wire:click="show({{$b->id}})"><i class="eye icon"></i></div>
						@can('u perpus')
						<div class="ui button" data-tooltip="Edit" data-inverted="" wire:click="edit({{$b->id}})"><i class="edit icon"></i></div>
						@endcan
						@can('d perpus')
						<div class="ui button" data-tooltip="Delete" data-inverted="" wire:click="confirmDelete({{$b->id}})"><i class="trash icon"></i></div>
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
			{{$books->links('vendor.livewire.semantic')}}
		</div>
	</div>
	
	@can('c perpus')
	{{-- modal create --}}
	<div wire:ignore.self id="modalCreate" class="ui modal">
		<div class="header">
			Add New Book
		</div>
		<div class="content">
			<div class="description">
				<div class="ui form error">
					{{-- form --}}
					<div class="two fields">
						<div class="twelve wide field @error('title') error @enderror required">
							<label>Judul Buku</label>
							<input type="text" wire:model="title">
							@error('title') <div class="ui pointing red basic label">{{$message}}</div> @enderror
						</div>
						
						<div class="four wide field">
							<label>Arabic</label>
							<select wire:model="arabic" class="ui dropdown">
								<option value="2">Tidak</option>
								<option value="1">Ya</option>
							</select>
						</div>
					</div>
					<div class="two fields">
						<div class="field @error('author') error @enderror required">
							<label>Nama Penulis</label>
							<input type="text" wire:model="author">
							@error('author') <div class="ui pointing red basic label">{{$message}}</div> @enderror
						</div>
						<div class="field">
							<label>Katalog</label>
							<select wire:model="catalog" class="ui search selection dropdown">
								<option value="">Pilih katalog</option>
								@foreach ($catalogs as $cat)
								<option value="{{$cat->id}}">{{$cat->name}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="fields">
						<div class="ten wide field @error('publisher') error @enderror required">
							<label>Penerbit</label>
							<input type="text" wire:model="publisher">
							@error('publisher') <div class="ui pointing red basic label">{{$message}}</div> @enderror
						</div>
						<div class="three wide field @error('year') error @enderror required">
							<label>Tahun Terbit</label>
							<input type="text" wire:model="year">
							@error('year') <div class="ui pointing red basic label">{{$message}}</div> @enderror
						</div>
						<div class="three wide field @error('exemplar') error @enderror required">
							<label>Banyak Buku</label>
							<input type="text" wire:model="exemplar">
							@error('exemplar') <div class="ui pointing red basic label">{{$message}}</div> @enderror
						</div>
					</div>
					<div class="two fields">
						<div class="field">
							<label>Sumber Buku</label>
							<select wire:model="source" class="ui dropdown">
								<option value="">Pilih salah satu</option>
								<option value="Pesantren">Pesantren</option>
								<option value="Dana BOS">Dana BOS</option>
								<option value="Wakaf Santri">Wakaf Santri</option>
								<option value="Hibah / Sumbangan">Hibah / Sumbangan</option>
							</select>
						</div>
						<div class="field">
							<label>Tempat</label>
							<input type="text" wire:model="description">
						</div>
					</div>
					<div class="three fields">
						<div class="six wide field @error('purchased_at') error @enderror required">
							<label>Dibeli Tanggal</label>
							<input type="text" wire:model="purchased_at">
							@error('purchased_at') <div class="ui pointing red basic label">{{$message}}</div> @enderror
						</div>
						<div class="six wide field @error('price') error @enderror required">
							<label>Harga Beli Satuan</label>
							<div class="ui labeled input">
								<div class="ui label">
									Rp.
								</div>
								<input type="text" wire:model="price" style="text-align:right;">
							</div>
							@error('price') <div class="ui pointing red basic label">{{$message}}</div> @enderror
						</div>
						<div wire:ignore class="four wide field">
							<label>Dapat Dipinjamkan?</label>
							<select class="ui dropdown" wire:model="lendable">
								<option value="1">Ya</option>
								<option value="2">Tidak</option>
							</select>
						</div>
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
			Edit Existing Book
		</div>
		<div class="content">
			<div class="description">
				<div class="ui form error">
					{{-- form --}}
					<div class="two fields">
						<div class="twelve wide field @error('title') error @enderror required">
							<label>Judul Buku</label>
							<input type="text" wire:model="title">
							@error('title') <div class="ui pointing red basic label">{{$message}}</div> @enderror
						</div>
						
						<div class="four wide field">
							<label>Arabic</label>
							<select wire:model="arabic" class="ui dropdown disabled">
								<option value="2">Tidak</option>
								<option value="1">Ya</option>
							</select>
						</div>
					</div>
					<div class="two fields">
						<div class="field @error('author') error @enderror required">
							<label>Nama Penulis</label>
							<input type="text" wire:model="author">
							@error('author') <div class="ui pointing red basic label">{{$message}}</div> @enderror
						</div>
						<div class="field">
							<label>Katalog</label>
							<select wire:model="catalog" class="ui search selection dropdown">
								<option value="">Pilih katalog</option>
								@foreach ($catalogs as $cat)
								<option value="{{$cat->id}}">{{$cat->name}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="three fields">
						<div class="ten wide field @error('publisher') error @enderror required">
							<label>Penerbit</label>
							<input type="text" wire:model="publisher">
							@error('publisher') <div class="ui pointing red basic label">{{$message}}</div> @enderror
						</div>
						<div class="three wide field @error('year') error @enderror required">
							<label>Tahun Terbit</label>
							<input type="text" wire:model="year">
							@error('year') <div class="ui pointing red basic label">{{$message}}</div> @enderror
						</div>
						<div class="three wide field @error('exemplar') error @enderror required">
							<label>Banyak Buku</label>
							<input type="text" wire:model="exemplar">
							@error('exemplar') <div class="ui pointing red basic label">{{$message}}</div> @enderror
						</div>
					</div>
					<div class="two fields">
						<div class="field">
							<label>Sumber Buku</label>
							<select wire:model="source" class="ui dropdown">
								<option value="">Pilih salah satu</option>
								<option value="Pesantren">Pesantren</option>
								<option value="Dana BOS">Dana BOS</option>
								<option value="Wakaf Santri">Wakaf Santri</option>
								<option value="Hibah / Sumbangan">Hibah / Sumbangan</option>
							</select>
						</div>
						<div class="field">
							<label>Tempat</label>
							<input type="text" wire:model="description">
						</div>
					</div>
					<div class="three fields">
						<div class="six wide field @error('purchased_at') error @enderror required">
							<label>Dibeli Tanggal</label>
							<input type="text" wire:model="purchased_at">
							@error('purchased_at') <div class="ui pointing red basic label">{{$message}}</div> @enderror
						</div>
						<div class="six wide field @error('price') error @enderror required">
							<label>Harga Beli Satuan</label>
							<div class="ui labeled input">
								<div class="ui label">
									Rp.
								</div>
								<input type="text" wire:model="price" style="text-align:right;">
							</div>
							@error('price') <div class="ui pointing red basic label">{{$message}}</div> @enderror
						</div>
						<div wire:ignore class="four wide field">
							<label>Dapat Dipinjamkan?</label>
							<select class="ui dropdown" wire:model="lendable">
								<option value="1">Ya</option>
								<option value="2">Tidak</option>
							</select>
						</div>
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
			Book Details
		</div>
		<div class="scrolling content">
			<div class="description">
				<div class="ui grid">
					@if($bd)
					<div class="ui six wide column">
						<div class="ui large list">
							<div class="item">
								<div class="description">Judul Buku</div>
								<div class="header">{{$bd->title}}</div>
							</div>
							<div class="item">
								<div class="description">Penulis, Tahun</div>
								<div class="header">{{$bd->author}}, {{$bd->year}}</div>
							</div>
							<div class="item">
								<div class="description">No. DDC</div>
								<div class="header">{{$bd->catalog->catno ?? '-'}}</div>
							</div>
							<div class="item">
								<div class="description">Katalog Buku</div>
								<div class="header">{{$bd->catalog->name ?? '-'}}</div>
							</div>
							<div class="item">
								<div class="description">Penerbit</div>
								<div class="header">{{$bd->publisher ?? '-'}}</div>
							</div>
							<div class="item">
								<div class="description">Sumber Buku</div>
								<div class="header">{{$bd->source ?? '-'}}, {{$bd->purchased_at->isoFormat('LL')}}</div>
							</div>
							<div class="item">
								<div class="description">Tempat</div>
								<div class="header">{{$bd->description ?? '-'}}</div>
							</div>
						</div>
						@if ($bd->lendable)
						<div class="ui positive message">Buku ini dapat dipinjamkan.</div>
						@else
						<div class="ui negative message">Buku ini tidak dapat dipinjamkan.</div>
						
						@endif
					</div>
					<div class="ui ten wide column">
						<table class="ui selectable table">
							<thead>
								<tr>
									<th>#</th>
									<th>No. Induk Buku</th>
									<th>Kondisi</th>
									@can('d perpus')
									<th>Options</th>
									@endcan
								</tr>
							</thead>
							<tbody>
								@foreach ($bd->details as $i => $bk)
								<tr class="{{$search == $bk->barcode ? 'positive' : ''}} {{$bk->status == 3 ? 'error' : ''}}">
									<td>{{$i+1}}</td>
									<td>
										<h4 class="ui header">
											{{$bk->barcode}}
											<div class="sub header">
												@if($bk->status != 3)
												{{$bk->lended ? 'Dipinjam' : 'Tersedia'}}
												@else
												{{'Tidak tersedia'}}
												@endif
											</div>
										</h4>
									</td>
									<td>
										<div class="ui compact selection dropdown fluid @if($bk->lended || Auth::user()->cannot('r perpus')) disabled @endif">
											<div class="text">
												@switch($bk->status)
												@case(1) Baik @break
												@case(2) Rusak @break
												@default Hilang
												@endswitch
											</div>
											<i class="dropdown icon"></i>
											<div class="menu">
												@foreach ($sts as $ks => $st)
												<div class="item {{$ks == $bk->status ? 'selected' : ''}}" wire:click="editStatus({{$bk->id}}, {{$ks}})">{{$st}}</div>
												@endforeach
											</div>
										</div>
									</td>
									@can('d perpus')
									<td class="collapsing center aligned">
										<div class="ui basic icon tiny buttons">
											@if ($bd->details->count() > 1)
											<div class="ui button{{$bk->lended ? ' disabled' : ''}}" data-tooltip="Delete" data-inverted="" wire:click="destroySingle({{$bk->id}})">
												<i class="trash icon"></i>
											</div>
											@else
											<div class="ui button disabled"><i class="trash icon"></i></div>
											@endif
										</div>
									</td>
									@endcan
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
					@endif
				</div>
			</div>
		</div>
		<div class="actions">
			@can('r perpus')
			@if($bd)
			<a target="_blank" href="{{route('books.barcode', $bd->id)}}" class="ui left floated teal button">
				Download Barcode
			</a>
			@endif
			@endcan
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
						<div class="ui header">{{$idtodelete->title ?? ''}}</div>
						<p>Anda yakin ingin menghapus data buku ini?</p>
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
	
	@can('c perpus')
	{{-- modal upload --}}
	<div id="modalUpload" class="ui tiny modal">
		<div class="header">
			Import Excel
		</div>
		<div class="content">
			<div class="description">
				<form action="{{route('books.import')}}" method="post" enctype="multipart/form-data" id="importExcel" class="ui form">
					@csrf
					<div class="field">
						<label>File Excel</label>
						<div class="ui action input">
							<input type="text" placeholder="Pilih file" readonly>
							<input type="file" name="excel" style="display: none">
							<div id="attach" class="ui icon button">
								<i class="attach icon"></i>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
		<div class="actions">
			<div class="ui black deny button">
				Cancel
			</div>
			<div class="ui positive right labeled icon button" onclick="document.getElementById('importExcel').submit()">
				Import
				<i class="upload icon"></i>
			</div>
		</div>
	</div>
	@endcan
	
</div>