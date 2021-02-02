<?php

namespace App\Exports;

use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

use App\Lendingstudent;
use App\Lendingmember;

class ReportLendingsExport implements FromCollection, WithHeadings, WithTitle, WithCustomStartCell, WithMapping, WithStyles, ShouldAutoSize, WithEvents, WithColumnFormatting
{
	use RegistersEventListeners;
	private $no = 0;
	
	public function __construct($s, $e, $m)
	{
		$this->s = $s;
		$this->e = $e;
		$this->m = $m;
	}
	
	public function collection()
	{
		//
		$l = $this->getData();
		return $l;
	}
	
	public function map($l): array
	{
		$k = null;
		if($l->lended_at->diffInDays($l->returned_at) > 5) $k = 'Terlambat';
		if($l->bookdetail->status == 3) $k = 'Hilang';
		return [
			++$this->no,
			$l->bookdetail->barcode,
			$l->bookdetail->book->title,
			$l->bookdetail->book->author . ', ' . $l->bookdetail->book->year,
			$l->student->name ?? $l->member->name,
			$l->student->stambuk ?? $l->member->nik,
			Date::dateTimeToExcel($l->lended_at),
			Date::dateTimeToExcel($l->lended_at),
			$l->users()->wherePivot('returning', false)->first()->name ?? '',
			$l->returned_at ? Date::dateTimeToExcel($l->returned_at) : '',
			$l->returned_at ? Date::dateTimeToExcel($l->returned_at) : '',
			$l->users()->wherePivot('returning', true)->first()->name ?? '',
			$l->dayfine ?? $l->lostfine,
			$k,
		];
	}
	
	public function getData()
	{
		$students = Lendingstudent::whereDate('lended_at', '>=', $this->s)->whereDate('lended_at', '<=', $this->e)->get();
		$members	= Lendingmember::whereDate('lended_at', '>=', $this->s)->whereDate('lended_at', '<=', $this->e)->get();
		if($this->m == 1) return $students->sortByDesc('lended_at');
		elseif($this->m == 2) return $members->sortByDesc('lended_at');
		else return $members->mergeRecursive($students)->sortByDesc('lended_at');
	}
	
	public function columnFormats(): array
	{
		return [
			'B' => '#',
			'G' => NumberFormat::FORMAT_DATE_DDMMYYYY,
			'H'	=> NumberFormat::FORMAT_DATE_TIME3,
			'J' => NumberFormat::FORMAT_DATE_DDMMYYYY,
			'K'	=> NumberFormat::FORMAT_DATE_TIME3,
			// 'K' => NumberFormat::FORMAT_ACCOUNTING_IDR,
		];
	}
	
	public function title(): string { return 'DATA PEMINJAMAN'; } 
	
	public function startCell(): string { return 'A1'; }
	
	public function headings(): array
	{
		$t = 'NIK / STAMBUK';
		if($this->m == 1) $t = 'STAMBUK';
		if($this->m == 2) $t = 'NIK';
		return [[
			'NO.',
			'NO. INDUK BUKU',
			'JUDUL BUKU',
			'PENGARANG, TAHUN',
			'NAMA PEMINJAM',
			$t,
			'PEMINJAMAN',
			'',
			'',
			'PENGEMBALIAN',
			'',
			'',
			'DENDA',
			'KETERANGAN',
		], [
			'', '', '', '', '', '', 'TANGGAL', 'JAM', 'STAFF', 'TANGGAL', 'JAM', 'STAFF', '', '',
			]
		];
	}
	
	public static function afterSheet(AfterSheet $event)
	{
		// merging
		$event->sheet->getDelegate()->mergeCells('A1:A2');
		$event->sheet->getDelegate()->mergeCells('B1:B2');
		$event->sheet->getDelegate()->mergeCells('C1:C2');
		$event->sheet->getDelegate()->mergeCells('D1:D2');
		$event->sheet->getDelegate()->mergeCells('E1:E2');
		$event->sheet->getDelegate()->mergeCells('F1:F2');
		$event->sheet->getDelegate()->mergeCells('G1:I1');
		$event->sheet->getDelegate()->mergeCells('J1:L1');
		$event->sheet->getDelegate()->mergeCells('M1:M2');
		$event->sheet->getDelegate()->mergeCells('N1:N2');
	}
	
	public function styles(Worksheet $sheet) 
	{ 
		return [
			1 => ['font' => ['bold' => true, 'size' => 14]], 
			2 => ['font' => ['bold' => true, 'size' => 14]],
			'A1:N1' => ['alignment' => [
				// 'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
				'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
			],],
			'G1:J1' => ['alignment' => [
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			],],
		]; 
	}
	
	
}