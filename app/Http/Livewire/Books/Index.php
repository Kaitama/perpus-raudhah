<?php

namespace App\Http\Livewire\Books;

use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;
use App\Catalog;
use App\Book;
use App\Bookdetail;
use ZipArchive;
use Storage;

class Index extends Component
{
	use WithPagination;
	
	public $bd = null, $search, $searching, $editing = false, $idtoedit, $idtodelete, $nocat = 1;
	public $sts = [1 => 'Baik', 2 => 'Rusak', 3 => 'Hilang'];
	public $title, $author, $year, $publisher, $exemplar = 1, $source, $description, $purchased_at, $price = 0, $lendable = 1, $catalog, $arabic;
	public $catalogs;
	
	
	public function mount()
	{
		$this->purchased_at = date('d/m/Y');
		$this->catalogs = Catalog::all();
	}
	
	public function render()
	{
		$s = '%' . $this->search . '%';
		if ($this->search) {
			$this->searching = true;
			$this->nocat = 1;
		} else {
			$this->searching = false;
		}
		if($this->nocat == 2){
			$books = Book::where('catalog_id', '!=', null)->paginate(25);
		} elseif($this->nocat == 3){
			$books = Book::where('catalog_id', null)->paginate(25);
		} else {	
			$books = Book::where('title', 'like', $s)
			->orWhere('author', 'like', $s)
			->orWhere('year', 'like', $s)
			->orWhere('publisher', 'like', $s)
			->orWhere('source', 'like', $s)
			->orWhere('description', 'like', $s)
			->orWhereHas('details', function($q){
				$q->where('barcode', $this->search);
			})
			->latest()
			->paginate(25);
		}
		foreach ($books as  $b) {
			foreach ($b->details as  $d) {
				if($this->search == $d->barcode){
					$this->bd = $b;
					$this->emit('showModalDetails');
				}
			}
		}
		
		return view('livewire.books.index', ['books' => $books]);
	}
	
	public function create()
	{
		if($this->editing) 
		{
			$this->editing = false;
			$this->resetInput();
		}
		$this->emit('showModalCreate');
	}
	
	public function store()
	{
		$this->validate([
			'title' => 'required',
			'author' => 'required',
			'year'	=> 'required|numeric|digits:4',
			'purchased_at'	=> 'required|date_format:d/m/Y',
			'exemplar'	=> 'required|numeric|gte:1',
			'price'	=> 'numeric|gte:0',
			]
		);
		$b = Book::create([
			'catalog_id' => $this->catalog,
			'title'	=> $this->title,
			'author'	=> $this->author,
			'year'	=> $this->year,
			// 'isbn'	=> $this->isbn,
			'publisher'	=> $this->publisher,
			'source'	=> $this->source,
			'description'	=> $this->description,
			'purchased_at'	=>	Carbon::createFromFormat('d/m/Y', $this->purchased_at)->format('Y-m-d'),
			'price'	=> $this->price,
			'lendable'	=> $this->lendable == 1 ? true : false,
			]
		);
		
		$t = 10;
		$basebar = 0;
		if($this->arabic == 1) $t = 20;
		$string = '%' . substr($this->purchased_at, -4) . $t . '%';
		$oldbar = Bookdetail::where('barcode', 'like', $string)->orderBy('barcode', 'desc')->first();
		if($oldbar) {
			$barcode = $oldbar->barcode;
			$basebar = substr($barcode, -6);
		}
		
		for ($i=1; $i <= $this->exemplar; $i++) { 
			$n = $basebar + $i;
			Bookdetail::create([
				'book_id'	=> $b->id,
				'barcode'	=> substr($this->purchased_at, -4) . $t . str_pad($n, 6, '0', STR_PAD_LEFT),
				]
			);
		}
		
		session()->flash('success', 'Data buku berhasil ditambahkan.');
		$this->resetInput();
	}
	
	public function edit(Book $book)
	{
		if(!$this->editing) 
		{
			$this->editing = true;
		}
		if($this->idtoedit != $book->id)
		{
			$bc = $book->details->first()->barcode;
			$ar = substr($bc, 4, 2);
			$this->idtoedit = $book->id;
			$this->catalog = $book->catalog_id;
			$this->title = $book->title;
			$this->author = $book->author;
			$this->year = $book->year;
			// $this->isbn = $book->isbn;
			$this->publisher = $book->publisher;
			$this->exemplar = $book->details->count();
			$this->source = $book->source;
			$this->description = $book->description;
			$this->purchased_at = date('d/m/Y', strtotime($book->purchased_at));
			$this->price = $book->price;
			$this->lendable = $book->lendable ? 1 : 2;
			$this->arabic = $ar == 20 ? 1 : 2;
		}
		$this->emit('showModalEdit');
	}
	
	public function update()
	{
		$book = Book::find($this->idtoedit);
		$oldcount = $book->details->count();
		$this->validate([
			'title' => 'required',
			'author' => 'required',
			'year'	=> 'required|numeric|digits:4',
			'purchased_at'	=> 'required|date_format:d/m/Y',
			'exemplar'	=> 'required|numeric|gte:' . $oldcount,
			'price'	=> 'numeric|gte:0',
		], [
			'exemplar.gte' => 'Pengurangan jumlah exemplar dilakukan dengan menghapus detail buku.'
			]
		);
		$book->update([
			'catalog_id' => $this->catalog,
			'title'	=> $this->title,
			'author'	=> $this->author,
			'year'	=> $this->year,
			// 'isbn'	=> $this->isbn,
			'publisher'	=> $this->publisher,
			'source'	=> $this->source,
			'description'	=> $this->description,
			'purchased_at'	=>	Carbon::createFromFormat('d/m/Y', $this->purchased_at)->format('Y-m-d'),
			'price'	=> $this->price,
			'lendable'	=> $this->lendable == 1 ? true : false,
			]
		);
		if ($this->exemplar > $oldcount) {
			
			$t = 10;
			$basebar = 0;
			if($this->arabic == 1) $t = 20;
			$string = '%' . substr($this->purchased_at, -4) . $t . '%';
			$oldbar = Bookdetail::where('barcode', 'like', $string)->orderBy('barcode', 'desc')->first();
			if($oldbar) {
				$barcode = $oldbar->barcode;
				$basebar = substr($barcode, -6);
			}
			
			for ($i = 1; $i <= $this->exemplar - $oldcount; $i++) { 
				$n = $basebar + $i;
				Bookdetail::create([
					'book_id'	=> $book->id,
					'barcode'	=> substr($this->purchased_at, -4) . $t . str_pad($n, 6, '0', STR_PAD_LEFT),
					]
				);
			}
		}
		session()->flash('success', 'Data buku berhasil diubah.');
		$this->resetInput();
	}
	
	public function show(Book $book)
	{
		$this->bd = $book;
		$this->emit('showModalDetails');
	}
	
	public function editStatus($id, $st)
	{
		Bookdetail::find($id)->update(['status' => $st]);
	}
	
	public function confirmDelete(Book $b)
	{
		$this->idtodelete = $b;
		$this->emit('showModalDelete');
	}
	
	public function destroy()
	{
		$book = $this->idtodelete;
		if ($book->details()->where('status', 2)->first()) {
			session()->flash('error', 'Tidak dapat menghapus karena ada buku yang sedang dipinjamkan.');
		} else {
			$book->delete();
			$this->idtodelete = null;
			session()->flash('success', 'Data buku berhasil dihapus.');
		}
	}
	
	public function destroySingle(Bookdetail $book)
	{
		$nib = $book->barcode;
		$book->delete();
		$this->emit('refreshModal');
		session()->flash('success', 'Buku dengan nomor induk ' . $nib . ' berhasil dihapus.');
	}
	
	private function resetInput()
	{
		$this->title = null; $this->author = null; $this->year = null; /* $this->isbn = null; */ $this->publisher = null; $this->exemplar = 1;
		$this->source = null; $this->description = null; $this->purchased_at = date('d/m/Y'); $this->price = 0; $this->lendable = 1; $this->idtoedit = null; $this->catalog = null;
	}
	
	public function downloadBarcode(){
		$details = Bookdetail::all();
		$public_dir=public_path();
		// Zip File Name
		$zipFileName = 'barcode_buku.zip';
		// Create ZipArchive Obj
		$zip = new ZipArchive;
		if ($zip->open($public_dir . '/' . $zipFileName, ZipArchive::CREATE) === TRUE) {
			$filearray = array();
			// Add Multiple file   
			foreach($details as $k => $file) {
				$barcode = \DNS1D::getBarcodePNG($file->barcode, "C128", 3, 64);
				Storage::disk('barcode')->put($file->barcode . '.png', base64_decode($barcode));
				$filename = $file->barcode . '.png';
				$zip->addFile(public_path('barcode/' . $file->barcode . '.png'), $filename);
				array_push($filearray, $filename);
			}        
			$zip->close();
			Storage::disk('barcode')->delete($filearray);
		}
		// Set Header
		$headers = array(
			'Content-Type' => 'application/octet-stream',
		);
		$filetopath=$public_dir.'/'.$zipFileName;
		// Create Download Response
		if(file_exists($filetopath)){
			return response()->download($filetopath,$zipFileName,$headers);
		}
	}
	
}

// setting ulang nomor barcode jika satu buku dihapus, kemudian jlh eksemplar ditambahkan, nomor barcode bisa duplikat!