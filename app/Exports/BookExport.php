<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Book;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BookExport implements WithMultipleSheets
{
	use Exportable;
	
	public function sheets(): array
	{
		$sheets = [];
		$sheets[] = new BuildData();
		return $sheets;
	}
	
}

class BuildData implements FromCollection, ShouldAutoSize, WithHeadings, WithCustomStartCell, WithTitle, WithMapping
{
	private $no = 0;

	public function collection()
	{
		$b = Book::all();	
		return $b;
	}

	public function map($b): array
	{
		return [
			++$this->no,
			$b->catalog['catno'],
			$b->catalog['name'],
			$b->title,
			$b->author,
			$b->year,
			$b->publisher,
			$b->source,
			$b->description,
			$this->formatDateId($b->purchased_at),
			$b->price,
			$b->details->count(),
			$b->details->where('lended', true)->count(),
			$b->details->where('status', 2)->count(),
			$b->details->where('status', 3)->count(),
		];
	}

	public function title(): string { return 'DATA BUKU'; } 

	public function startCell(): string { return 'A1'; }

	public function headings(): array 
	{
		return [
			'NO.',
			'DDC',
			'KATALOG',
			'JUDUL BUKU',
			'PENGARANG',
			'TAHUN TERBIT',
			'PENERBIT',
			'SUMBER BUKU',
			'TEMPAT',
			'TANGGAL PEMBELIAN',
			'HARGA SATUAN',
			'JLH. EKSEMPLAR',
			'DIPINJAM',
			'RUSAK',
			'HILANG'
		];
	}

	private function formatDateId($datetime)
	{
		try {
			$date = date('Y-m-d', strtotime($datetime));
			$d = explode('-', $date);
			$dt = $d[2] . '/' . $d[1] . '/' . $d[0];
			return $dt;
		} catch (\Throwable $th) {
			//throw $th;
		}
	}

}