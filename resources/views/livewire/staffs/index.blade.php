<div>
	@include('components.flashmessage')
	<div class="ui three column stackable grid">
		<div class="column">
			<div class="ui icon input fluid">
				<input type="text" placeholder="Cari staff..." wire:model.debounce.500ms="search" autofocus>
				@if ($searching)
				<i class="inverted circular times link icon" wire:click="resetSearch"></i>
				@endif
			</div>
		</div>
		<div class="column computer only"></div>
		<div class="column right aligned">
			@role('administrator')
			<div wire:click="create" class="ui positive labeled icon button">
				<i class="plus icon"></i> Add Staff
			</div>
			@endrole
		</div>
	</div>
	
	
	@if($users->isEmpty())
	<div class="ui icon message">
		<i class="info icon"></i>
		<div class="content">
			<div class="header">Record empty!</div>
			<p>Data pegawai masih kosong.</p>
		</div>
	</div>
	@else
	
	<table class="ui selectable table">
		<thead>
			<tr>
				<th>#</th>
				<th>Nama Lengkap</th>
				<th>Role</th>
				<th>Status</th>
				@role('administrator')
				<th>Options</th>
				@endrole
			</tr>
		</thead>
		<tbody>
			@foreach ($users as $u)
			<tr>
				<td class="collapsing">{{$n++}}</td>
				<td>
					<h5 class="ui header">
						{{$u->name}}
						<div class="sub header">
							<i class="envelope outline icon"></i>{{$u->email}}
						</div>
					</h5>
				</td>
				<td class="collapsing">
						{{ucwords($u->getRoleNames()->implode(', '))}} 
				</td>
				<td class="collapsing">
					<h5 class="ui header">
						@if ($u->status)
						<a class="ui tiny green label" data-tooltip="{{$u->name}} sedang login." data-position="left center">Online</a>
						@else
						<a class="ui tiny label" @if($u->state) data-tooltip="Terakhir login {{$u->state->lastseen_at->diffForHumans()}}." @else data-tooltip="{{$u->name}} belum pernah login." @endif data-position="left center">Offline</a>
						@endif
					</h5>
				</td>
				@role('administrator')
				<td class="collapsing">
					<div class="ui basic icon buttons">
						<div wire:click="edit({{$u->id}})" class="ui button" data-tooltip="Edit" data-inverted=""><i class="edit icon"></i></div>
						<div wire:click="confirmDelete({{$u->id}})" class="ui button{{Auth::id() == $u->id ? ' disabled' : ''}}" data-tooltip="Delete" data-inverted=""><i class="trash icon"></i></div>
					</div>
				</td>
				@endrole
			</tr>
			@endforeach
		</tbody>
	</table>
	
	<div class="ui grid">
		<div class="column center aligned">
			{{$users->links('vendor.livewire.semantic')}}
		</div>
	</div>
	
	@endif
	
	@role('administrator')
	{{-- modal create --}}
	<div wire:ignore.self id="modalCreate" class="ui modal">
		<div class="header">
			Add New Staff
		</div>
		<div class="content">
			<div class="description">
				<div class="ui form error">
					{{-- form --}}
					<div class="two fields">
						<div class="field @error('name') error @enderror required">
							<label>Nama Lengkap</label>
							<input type="text" wire:model="name">
							@error('name') <div class="ui pointing red basic label">{{$message}}</div> @enderror
						</div>
						<div class="field @error('role') error @enderror required">
							<label>Nama Lengkap</label>
							<select wire:model="role" class="ui dropdown">
								<option value="">Pilih role staff</option>
								@foreach ($roles as $r)
								<option value="{{$r->name}}">{{ucwords($r->name)}}</option>
								@endforeach
							</select>
							@error('role') <div class="ui pointing red basic label">{{$message}}</div> @enderror
						</div>
					</div>
					<div class="two fields">
						<div class="field @error('username') error @enderror required">
							<label>Login Username</label>
							<input type="text" wire:model.lazy="username">
							@error('username') <div class="ui pointing red basic label">{{$message}}</div> @enderror
						</div>
						<div class="field @error('email') error @enderror required">
							<label>Email</label>
							<input type="email" wire:model.lazy="email">
							@error('email') <div class="ui pointing red basic label">{{$message}}</div> @enderror
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
	
	{{-- modal edit --}}
	<div wire:ignore.self id="modalEdit" class="ui modal">
		<div class="header">
			Edit Staff
		</div>
		<div class="content">
			<div class="description">
				<div class="ui form error">
					{{-- form --}}
					<div class="two fields">
						<div class="field @error('name') error @enderror required">
							<label>Nama Lengkap</label>
							<input type="text" wire:model="name">
							@error('name') <div class="ui pointing red basic label">{{$message}}</div> @enderror
						</div>
						<div class="field @error('role') error @enderror required">
							<label>Nama Lengkap</label>
							<select wire:model="role" class="ui dropdown">
								<option value="">Pilih role staff</option>
								@foreach ($roles as $r)
								<option value="{{$r->name}}">{{ucwords($r->name)}}</option>
								@endforeach
							</select>
							@error('role') <div class="ui pointing red basic label">{{$message}}</div> @enderror
						</div>
					</div>
					<div class="two fields">
						<div class="field @error('username') error @enderror required">
							<label>Login Username</label>
							<input type="text" wire:model.lazy="username">
							@error('username') <div class="ui pointing red basic label">{{$message}}</div> @enderror
						</div>
						<div class="field @error('email') error @enderror required">
							<label>Email</label>
							<input type="email" wire:model.lazy="email">
							@error('email') <div class="ui pointing red basic label">{{$message}}</div> @enderror
						</div>
					</div>
					{{--  --}}
				</div>
			</div>
		</div>
		<div class="actions">
			<div wire:click="resetPassword" class="ui labeled icon button left floated">
				<i class="lock icon"></i>
				Reset Password
			</div>
			<div class="ui black deny button">
				Cancel
			</div>
			<div class="ui positive right labeled icon button" wire:click="update()">
				Save
				<i class="save icon"></i>
			</div>
		</div>
	</div>
	
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
						<div class="ui header">{{$idtodelete->name ?? ''}}</div>
						<p>Anda yakin ingin menghapus data staff ini?</p>
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
	@endrole
	
</div>
