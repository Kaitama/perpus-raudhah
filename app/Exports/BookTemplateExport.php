<?php

namespace App\Exports;

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
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

use App\Catalog;

class BookTemplateExport implements WithMultipleSheets
{
	use Exportable;
	
	public function sheets(): array
	{
		$sheets = [];
		$sheets[] = new BookTemplate();
		$sheets[] = new CatalogData();
		return $sheets;
	}
	
}

class BookTemplate implements ShouldAutoSize, WithStyles, WithHeadings, WithCustomStartCell, WithTitle, WithEvents, WithColumnFormatting
{
	use RegistersEventListeners;

	public function styles(Worksheet $sheet) { return [1 => ['font' => ['bold' => true, 'size' => 16]]]; }
	
	public function collection(){ return collect(); }

	public function startCell(): string { return 'A1'; }
	
	public function title(): string { return 'DATA BUKU'; } 

	public function headings(): array
	{
		return [
			'NO.',
			'ID KATALOG',
			'JUDUL BUKU',
			'PENGARANG',
			'TAHUN TERBIT',
			'PENERBIT',
			'JLH. EKSEMPLAR',
			'SUMBER BUKU',
			'TEMPAT',
			'TANGGAL PEMBELIAN',
			'HARGA SATUAN',
			'DAPAT DIPINJAMKAN (Y/T)',
			'ARABIC (Y/T)'
		];
	}

	public static function afterSheet(AfterSheet $event)
	{
		$sheet = $event->sheet->getDelegate();
		$sheet->getComment('B1')->getText()->createTextRun('Hanya isi ID KATALOG atau boleh dikosongkan.');
		$sheet->getComment('C1')->getText()->createTextRun('Judul buku bisa diisi dengan karakter Arabic.');
		$sheet->getComment('E1')->getText()->createTextRun('Hanya diisi empat digit tahun terbit buku.');
		$sheet->getComment('G1')->getText()->createTextRun('Hanya diisi angka jumlah eksemplar buku.');
		$sheet->getComment('H1')->getText()->createTextRun('1. Pesantren; 2. Dana BOS; 3. Wakaf Santri; 4. Hibah.');
		$sheet->getComment('J1')->getText()->createTextRun('Format dd/mm/yyyy | Ubah format kolom menjadi TEXT.');
		$sheet->getComment('K1')->getText()->createTextRun('Hanya diisi angka, tanpa simbol Rp atau titik.');
		$sheet->getComment('L1')->getText()->createTextRun('Jika dikosongkan akan dianggap tidak dapat dipinjamkan (T).');
		$sheet->getComment('M1')->getText()->createTextRun('Jika dikosongkan akan dianggap bukan Arabic (T).');
		
		$sheet->getStyle('C1:H1')->getFont()->getColor()->setRGB('ff0000');
		$sheet->getStyle('J1:L1')->getFont()->getColor()->setRGB('ff0000');
		$sheet->getStyle('A1:L1')->getBorders()->getAllBorders()
		->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
		$sheet->getRowDimension('1')->setRowHeight(36);
	}

	public function columnFormats(): array
    {
        return [
            // 'J' => NumberFormat::FORMAT_TEXT,
            // 'C' => NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE,
        ];
    }
	
}

class CatalogData implements FromCollection, WithTitle, WithStyles, WithHeadings, ShouldAutoSize, WithCustomStartCell
{
	
	public function collection()
	{
		//
		$catalogs = Catalog::all();
		foreach ($catalogs as $catalog) {
			unset($catalog['created_at']);
			unset($catalog['updated_at']);
		}
		return $catalogs->sortBy('id');
	}
	
	public function startCell(): string { return 'A1'; }
	
	public function headings(): array { return ['ID KATALOG', 'NOMOR DDC', 'SUBJEK KATALOG'];	}
	
	public function title(): string { return 'DATA KATALOG'; }
	
	public function styles(Worksheet $sheet) { return [1 => ['font' => ['bold' => true, 'size' => 16]]]; }
	
}