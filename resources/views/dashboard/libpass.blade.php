<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="Content-Type" content="application/pdf; charset=utf-8"/>
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="stylesheet" href="{{asset('semantic/semantic.css')}}">
	<title>Document</title>
	<style>
		
	</style>
</head>
<body>
	<div class="ui basic segment">
		<table>
			<tr>
				<td><img src="{{asset('img/app/logo.png')}}" alt="" class="ui middle aligned tiny image"></td>
				<td style="padding-left: 24px; text-align: center">
					<h2 class="ui header center aligned">
						PERPUSTAKAAN {{$student->gender == 'L' ? 'PUTRA' : 'PUTRI'}}
						<br>
						PESANTREN AR-RAUDLATUL HASANAH
					</h2>
					<div class="sub header">Jl. Letjend Jamin Ginting Km.11 Paya Bundung Medan 20135 Telp. (061) 8360135</div>
				</td>
			</tr>
		</table>
		<hr>
		<h3 class="ui header center aligned">
			SURAT KETERANGAN BEBAS PUSTAKA
			<div class="sub header">{{date('Y')}}/PERPUS/{{$student->gender == 'L' ? 'P' : 'W'}}/{{$student->stambuk}}</div>
		</h3>
		<br><br>
		<p>Yang bertanda tangan di bawah ini menerangkan bahwa:</p>
		<table>
			<tr>
				<td class="collapsing">Stambuk</td>
				<td class="collapsing">: &nbsp;</td>
				<td>{{$student->stambuk}}</td>
			</tr>
			<tr>
				<td class="collapsing">Nama Lengkap</td>
				<td class="collapsing">: &nbsp;</td>
				<td>{{ucwords(strtolower($student->name))}}</td>
			</tr>
			<tr>
				<td class="collapsing">Kelas</td>
				<td class="collapsing">: &nbsp;</td>
				<td>{{ucwords($student->classroom->name)}}</td>
			</tr>
		</table>
		<br>
		<p>Santri tersebut tidak memiliki pinjaman koleksi buku milik Perpustakaan {{$student->gender == 'L' ? 'Putra' : 'Putri'}} Pesantren Ar-Raudlatul Hasanah.</p>

		<table>
			<tr>
				<td>&nbsp;</td>
				<td width="24%" style="text-align: center">
				Medan, {{$now->isoFormat('LL')}} <br>
				Staff Perpustakaan,
				<br><br><br><br><br>
				<h5>{{Auth::user()->name}}</h5>
				</td>
			</tr>
		</table>
	</div>
</body>
</html>