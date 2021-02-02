<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;
use App\Book;

class BarcodeController extends Controller
{
	//
	public function download($id)
	{
		$book = Book::find($id);
		$pdf = PDF::loadView('dashboard.bookbarcode',['book' => $book]);
		return $pdf->stream('CETAK BARCODE BUKU.pdf');
	}
}