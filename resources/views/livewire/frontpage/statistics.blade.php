<div>
	<div class="ui cards">
		<div class="blue card">
			<div class="content center aligned">
				<div class="ui blue statistic">
					<div class="value">
						{{$books->count()}}
					</div>
					<div class="label">
						Judul Buku
					</div>
				</div>
			</div>
		</div>
		<div class="teal card">
			<div class="content center aligned">
				<div class="ui teal statistic">
					<div class="value">
						{{$bookdetails->where('status', '!=', 3)->count()}}
					</div>
					<div class="label">
						Eksemplar
					</div>
				</div>
			</div>
		</div>
		<div class="orange card">
			<div class="content center aligned">
				<div class="ui orange statistic">
					<div class="value">
						{{$members->count()}}
					</div>
					<div class="label">
						Members
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
