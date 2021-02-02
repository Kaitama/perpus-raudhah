<?php

namespace App\Http\Livewire\Frontpage;

use Livewire\Component;
use App\Book;

class Index extends Component
{
	public $search, $searching, $bookview;

	public function render()
	{
		if(strlen($this->search) >= 3) $this->searching = true; else $this->searching = false;
		$string = '%' . $this->search . '%';
		$books = Book::where('title', 'like', $string)->orWhere('author', 'like', $string)->get();
		return view('livewire.frontpage.index', ['books' => $books]);
	}

	public function showDetails($id)
	{
		$this->bookview = Book::find($id);
		$this->emit('showModalDetails');
	}
}