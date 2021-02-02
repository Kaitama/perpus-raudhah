<div>
	<div class="ui text container">
		<h1 class="ui inverted header">Perpustakaan Pesantren
		<br>Ar-Raudlatul Hasanah</h1>
	</div>
	<div class="ui text container" style="margin-top: 20px; text-align: left!important">
		<div class="ui input icon fluid huge">
			<i class="search icon"></i>
			<input wire:model.debounce.500ms="search" type="text" placeholder="Cari judul buku atau nama pengarang.." autofocus>
		</div>
		
		@if ($searching)
		<div class="ui middle aligned divided list inverted">
			@foreach ($books as $book)
			@if ($book->lendable)
			<a wire:click="showDetails({{$book->id}})" class="item">
				<div class="content">
					<div class="header">{{$book->title}}</div>
					<div class="desription">{{$book->author}}, {{$book->year}}</div>
				</div>
			</a>
			@endif
			@endforeach
		</div>
		@endif
	</div>
	
	@if ($bookview)
	{{-- modal details --}}
	<div wire:ignore.self id="modalDetails" class="ui small modal">
		<div class="header">
			Book Details
		</div>
		<div class="scrolling content">
			<div class="description">
				<div class="ui grid">
					<div class="column">
						<div class="ui large list">
							<div class="item">
								<div class="description">Judul Buku</div>
								<div class="header">{{$bookview->title}}</div>
							</div>
							<div class="item">
								<div class="description">Penulis, Tahun</div>
								<div class="header">{{$bookview->author}}, {{$bookview->year}}</div>
							</div>
							<div class="item">
								<div class="description">No. DDC</div>
								<div class="header">{{$bookview->catalog->catno ?? '-'}}</div>
							</div>
							<div class="item">
								<div class="description">Katalog Buku</div>
								<div class="header">{{$bookview->catalog->name ?? '-'}}</div>
							</div>
							<div class="item">
								<div class="description">Penerbit</div>
								<div class="header">{{$bookview->publisher ?? '-'}}</div>
							</div>
							<div class="item">
								<div class="description">Tempat</div>
								<div class="header">{{$bookview->description ?? '-'}}</div>
							</div>
							<div class="item">
								<div class="description">Banyak Buku</div>
								<div class="header">{{$bookview->details->where('lended', false)->where('status', '!=', 3)->count()}} Tersedia</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="actions">
			<div class="ui black deny button">
				Close
			</div>
		</div>
	</div>
	@endif
</div>
