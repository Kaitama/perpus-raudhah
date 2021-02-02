<?php

namespace App\Imports;

use App\Book;
use App\Bookdetail;
use Carbon\Carbon;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;


class BookImport implements WithMultipleSheets
{
	public function sheets(): array
	{
		return [
			0 => new BookData(),
		];
	}
}

class BookData implements ToModel, WithStartRow
{
	
	public function model(array $row)
	{
		$source = null;
		switch ($row[7]) {
			case 1: $source = 'Pesantren'; break;
			case 2: $source = 'Dana BOS'; break;
			case 3: $source = 'Wakaf Santri'; break;
			default: $source = 'Hibah / Sumbangan'; break;
		}
		$book = Book::create([
			'catalog_id' => $row[1],
			'title'	=> $row[2],
			'author' => $row[3],
			'year' => $row[4],
			'publisher' => $row[5],
			'source' => $source,
			'description' => $row[8],
			'purchased_at' => $this->idDateFormat($row[9]),
			'price' => $row[10],
			'lendable' => $row[11] == 'Y' ? true : false,
			]
		);
		$t = 10;
		$basebar = 0;
		if($row[12] == 'Y') $t = 20;
		$string = '%' . substr($row[9], -4) . $t . '%';
		$oldbar = Bookdetail::where('barcode', 'like', $string)->orderBy('barcode', 'desc')->first();
		if($oldbar) {
			$barcode = $oldbar->barcode;
			$basebar = substr($barcode, -6);
		}
		for ($i=1; $i <= $row[6]; $i++) { 
			$n = $basebar + $i;
			Bookdetail::create([
				'book_id' => $book->id,
				'barcode' => substr($row[9], -4) . $t . str_pad($n, 6, '0', STR_PAD_LEFT),
				]
			);
		}
		
		return $book;
		
	}
	
	public function startRow(): int
	{
		return 2;
	}
	
	private function idDateFormat($val)
	{
		try {
			$dt = explode('/', $val);
			$d = $dt[1] . '/' . $dt[0] . '/' . $dt[2];
			$x = strtotime($d);
			return date('Y-m-d', $x);
		} catch (\ErrorException $e) {
			return $e;
		}
	}
	
}