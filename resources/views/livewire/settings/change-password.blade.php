<div>
	<div id="modalChangePassword" class="ui tiny modal">
		<div class="header">
			Change Password
		</div>
		<div class="content">
			<div class="description">
				<div class="ui form error">
					<div class="field required @error('old_password') error @enderror">
						<label>Password Lama</label>
						<input type="password" wire:model="old_password">
						@error('old_password') <div class="ui pointing red basic label">{{$message}}</div> @enderror
					</div>
					<div class="field required @error('new_password') error @enderror">
						<label>Password Baru</label>
						<input type="password" wire:model="new_password">
						@error('new_password') <div class="ui pointing red basic label">{{$message}}</div> @enderror
					</div>
					<div class="field required @error('new_password_confirmation') error @enderror">
						<label>Konfirmasi Password</label>
						<input type="password" wire:model="new_password_confirmation">
						@error('new_password_confirmation') <div class="ui pointing red basic label">{{$message}}</div> @enderror
					</div>
				</div>
			</div>
		</div>
		<div class="actions">
			<div class="ui black deny button">
				Cancel
			</div>
			<div class="ui positive labeled icon button" wire:click.prevent="changepass">
				<i class="lock icon"></i>
				Submit
			</div>
		</div>
	</div>
</div>
