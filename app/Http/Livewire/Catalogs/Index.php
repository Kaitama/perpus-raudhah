<?php

namespace App\Http\Livewire\Catalogs;

use Livewire\Component;
use Livewire\WithPagination;
use App\Catalog;
use App\Book;

class Index extends Component
{
	use WithPagination;
	
	public $catalog, $bd = null, $search, $searching, $editing = false, $idtoedit, $idtodelete;
	
	public $catalog_number = null, $catalog_name = null, $description = null;
	
	public function render()
	{
		$s = '%' . $this->search . '%';
		if ($this->search) {
			$this->searching = true;
		} else {
			$this->searching = false;
		}
		$catalogs = Catalog::where('catno', 'like', $s)
		->orWhere('name', 'like', $s)
		->orWhere('description', 'like', $s)
		->latest()
		->paginate(25);
		
		return view('livewire.catalogs.index', ['catalogs' => $catalogs]);
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
		$this->validate(['catalog_number' => 'required', 'catalog_name'	=> 'required']);
		Catalog::create([
			'catno' => $this->catalog_number,
			'name'	=> $this->catalog_name,
			'description'	=> $this->description,
			]
		);
		
		session()->flash('success', 'Data katalog berhasil ditambahkan.');
		$this->resetInput();
	}

	public function show(Catalog $catalog)
	{
		$this->catalog = $catalog;
		$this->emit('showModalDetails');
	}
	
	public function edit(Catalog $catalog)
	{
		if(!$this->editing) 
		{
			$this->editing = true;
		}
		if($this->idtoedit != $catalog->id)
		{
			$this->idtoedit = $catalog->id;
			$this->catalog_number = $catalog->catno;
			$this->catalog_name = $catalog->name;
			$this->description = $catalog->description;
		}
		$this->emit('showModalEdit');
	}
	
	public function update()
	{
		$this->validate(['catalog_number' => 'required', 'catalog_name'	=> 'required']);
		Catalog::find($this->idtoedit)->update([
			'catno' => $this->catalog_number,
			'name'	=> $this->catalog_name,
			'description'	=> $this->description,
			]
		);
		
		session()->flash('success', 'Data katalog berhasil diubah.');
		$this->resetInput();
	}
	
	public function confirmDelete(Catalog $catalog)
	{
		$this->idtodelete = $catalog;
		$this->emit('showModalDelete');
	}
	
	public function destroy()
	{
		$catalog = $this->idtodelete;
		$catalog->delete();
		$this->idtodelete = null;
		session()->flash('success', 'Data katalog berhasil dihapus.');
	}
	
	private function resetInput()
	{
		$this->catalog_number = null; $this->catalog_name = null; $this->description = null; $this->idtoedit = null;
	}
	
}