<?php

namespace App\Http\Livewire\Report\Lendings;

use Auth;

use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;
use App\Lendingstudent;
use App\Lendingmember;

class Index extends Component
{
	use WithPagination;
	
	public $no = 1, $s, $e;

	// filter
	public $startDate, $endDate, $membership = 0;

	public function mount()
	{
		$now = Carbon::now();
		$this->startDate = $now->firstOfMonth()->format('d/m/Y');
		// $this->startDate = Carbon::yesterday()->format('d/m/Y');
		$this->endDate = Carbon::today()->format('d/m/Y');
	}

	public function render()
	{
		$lendings = $this->filter();
		return view('livewire.report.lendings.index', ['lendings' => $lendings]);
	}

	public function filter()
	{
		$sd = Carbon::createFromFormat('d/m/Y', $this->startDate)->format('Y-m-d');
		$ed = Carbon::createFromFormat('d/m/Y', $this->endDate)->format('Y-m-d');
		$this->s = $sd; $this->e = $ed;
		$students = Lendingstudent::whereDate('lended_at', '>=', $sd)->whereDate('lended_at', '<=', $ed)->get();
		$members	= Lendingmember::whereDate('lended_at', '>=', $sd)->whereDate('lended_at', '<=', $ed)->get();
		if($this->membership == 1) return $students->sortByDesc('lended_at');
		elseif($this->membership == 2) return $members->sortByDesc('lended_at');
		else return $members->mergeRecursive($students)->sortByDesc('lended_at');
	}

}