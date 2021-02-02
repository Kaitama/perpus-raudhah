<div>
	@include('components.flashmessage')
	
	<div class="ui stackable grid">
		<div class="five wide column">
			<div class="ui fluid card">
				<div class="image">
					<img src="{{url('https://sisfo.raudhah.ac.id/assets/img/user')}}/{{$user->photo ?? 'nopic.png'}}">
				</div>
				<div class="content">
					<a class="header">{{$user->name}}</a>
					<div class="meta">
						<span class="date">{{ucwords($user->getRoleNames()->implode(', '))}}</span>
					</div>
					<div class="description">
						<div class="ui list">
							<div class="content">
								<div class="item">
									<i class="ui mail icon"></i>{{$user->email}}
								</div>
								<div class="item">
									<i class="ui user icon"></i>{{$user->username}}
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="extra content">
					<a href="#" class="changepassword">
						<i class="cog icon"></i>
						Ubah Password
					</a>
				</div>
			</div>
			
		</div>
		<div class="eleven wide column">
			<h2 class="ui header">Statistik Perpustakaan</h2>
			
			<div class="ui segment">
				<div class="ui three statistics">
					<div class="statistic">
						<div class="value">{{$books->count()}}</div>
						<div class="label">Judul Buku</div>
					</div>
					<div class="statistic">
						<div class="value">{{$bookdetails->where('status', '!=', 3)->count()}}</div>
						<div class="label">Eksemplar</div>
					</div>
					<div class="statistic">
						<div class="value">{{$bookdetails->where('status', 2)->count()}}</div>
						<div class="label">Buku Rusak</div>
					</div>
					<div class="statistic">
						<div class="value">{{$bookdetails->where('status', 3)->count()}}</div>
						<div class="label">Buku Hilang</div>
					</div>
					<div class="statistic">
						<div class="value">{{$bookdetails->where('lended', true)->count()}}</div>
						<div class="label">Dipinjam</div>
					</div>
					<div class="statistic">
						<div class="value">{{$bookdetails->where('lended', false)->count() - $bookdetails->where('status', 3)->count()}}</div>
						<div class="label">Tersedia</div>
					</div>
				</div>
			</div>
			
			<div class="ui segment">
				<div class="ui three statistics">
					<div class="statistic">
						<div class="value">{{$members->count()}}</div>
						<div class="label">Member</div>
					</div>
					<div class="statistic">
						<div class="value">{{$students->where('gender', 'L')->count()}}</div>
						<div class="label">Santri Putra</div>
					</div>
					<div class="statistic">
						<div class="value">{{$students->where('gender', 'P')->count()}}</div>
						<div class="label">Santri Putri</div>
					</div>
				</div>
			</div>
			
			<h2 class="ui header">Aktifitas Hari Ini</h2>
			<div class="ui segment">
				<div class="ui two statistics">
					<div class="statistic">
						<div class="value">{{$lmembers->count() + $lstudents->count()}}</div>
						<div class="label">Peminjaman</div>
					</div>
					<div class="statistic">
						<div class="value">{{$rmembers->count() + $rstudents->count()}}</div>
						<div class="label">Pengembalian</div>
					</div>
				</div>
				<div class="ui divider"></div>
				<div class="ui two column divided grid">
					<div class="column">
						<div class="ui two statistics">
							<div class="statistic">
								<div class="value">{{$lmembers->count()}}</div>
								<div class="label">Member</div>
							</div>
							<div class="statistic">
								<div class="value">{{$lstudents->count()}}</div>
								<div class="label">Santri</div>
							</div>
						</div>
					</div>
					<div class="column">
						<div class="ui two statistics">
							<div class="statistic">
								<div class="value">{{$rmembers->count()}}</div>
								<div class="label">Member</div>
							</div>
							<div class="statistic">
								<div class="value">{{$rstudents->count()}}</div>
								<div class="label">Santri</div>
							</div>
						</div>
					</div>
				</div>
			</div>


			<h2 class="ui header">Data Pengunjung</h2>
			<div class="ui segment">
				<div class="ui two statistics">
					<div class="statistic">
						<div class="value">0</div>
						<div class="label">Hari Ini</div>
					</div>
					<div class="statistic">
						<div class="value">0</div>
						<div class="label">Bulan Ini</div>
					</div>
				</div>
				<div class="ui divider"></div>
				<div class="ui two column divided grid">
					<div class="column">
						<div class="ui two statistics">
							<div class="statistic">
								<div class="value">0</div>
								<div class="label">Member</div>
							</div>
							<div class="statistic">
								<div class="value">0</div>
								<div class="label">Santri</div>
							</div>
						</div>
					</div>
					<div class="column">
						<div class="ui two statistics">
							<div class="statistic">
								<div class="value">0</div>
								<div class="label">Member</div>
							</div>
							<div class="statistic">
								<div class="value">0</div>
								<div class="label">Santri</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
		</div>
	</div>
</div>
