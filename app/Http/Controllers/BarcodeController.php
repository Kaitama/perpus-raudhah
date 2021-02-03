<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;
use App\Book;
use Carbon\Carbon;
use App\Student;

class BarcodeController extends Controller
{
	//
	public function download($id)
	{
		$book = Book::find($id);
		$pdf = PDF::loadView('dashboard.bookbarcode',['book' => $book]);
		return $pdf->stream('CETAK BARCODE BUKU.pdf');
	}

	public function libpass($id)
	{
			$now = Carbon::now();
			$student = Student::find($id);
			if($student->lendings->where('returned_at', null)->count() > 0) return abort(403);
			$pdf = PDF::loadView('dashboard.libpass', ['student' => $student, 'now' => $now]);
			return $pdf->stream('SURAT BEBAS PERPUSTAKAAN.pdf');
	}
}