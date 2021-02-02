<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="stylesheet" href="{{asset('semantic/semantic.css')}}">
	<title>Document</title>
	<style>
		.box {
			width: 4cm;
			border: 1px solid black!important;
			/* display: flex; */
		}
	</style>
</head>
<body>
	
	<table class="ui table">
		<tbody>
			<tr>
				<td class="collapsing">Judul Buku</td>
				<td class="collapsing">:</td>
				<td>{{$book->title}}</td>
			</tr>
			<tr>
				<td>Penulis</td>
				<td>:</td>
				<td>{{$book->author}}</td>
			</tr>
			<tr>
				<td>Tahun</td>
				<td>:</td>
				<td>{{$book->year}}</td>
			</tr>
		</tbody>
	</table>
	
	@foreach ($book->details as $dt)
	<div class="ui divided items" style="border-top: 1px dashed black; padding-top: 30px">
		<div class="item">
			<div class="image" style="text-align: center">
				@php 
				echo '<img class="ui centered image" src="data:image/png;base64,' . DNS1D::getBarcodePNG($dt->barcode, "C128", 2, 54, array(1,1,1), true) . '" alt="barcode"   />' 
				@endphp
				{{-- <br><span style="font-family: sans-serif;" text-anchor= "middle" >{{$dt->barcode}}</span> --}}
			</div>
		</div>
	</div>
	@endforeach
	<div style="border-top: 1px dashed black; padding-top: 30px"></div>
	
	
	
	<script src="{{asset('semantic/semantic.js')}}"></script>
</body>
</html>