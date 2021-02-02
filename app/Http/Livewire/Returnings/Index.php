<?php

namespace App\Http\Livewire\Returnings;

use Auth;

use Livewire\Component;
use Carbon\Carbon;
use App\Bookdetail;
use App\Student;
use App\Member;
use App\Lendingstudent;
use App\Lendingmember;

class Index extends Component
{
	public $uid, $lender, $ismember = true, $lended = 0, $alertmessage;
	public $bid, $books, $confirm, $returning_date, $dayfines, $lostfines, $brokenfines, $status, $condition;
	// public $rbooks;
	
	public function mount()
	{
		// $this->rbooks = collect();
		$this->books = collect();
		$this->returning_date = Carbon::now();
	}
	
	public function render()
	{
		return view('livewire.returnings.index');
	}
	
	public function searchUser()
	{
		$search = null;
		$this->books = collect();
		$this->lender = null;
		if($this->uid)
		{
			
			$member = Member::where('nik', $this->uid)->first();
			if($member) {
				$this->ismember = true;
				$this->lender = $member;
			} else {
				$student = Student::where('stambuk', $this->uid)->first();
				if($student) {
					$this->ismember = false;
					$this->lender = $student;
				}
			}
			if($this->lender){
				$this->lended = $this->lender->lendings->where('returned_at', null)->count();
				if($this->lended > 0) $this->emit('inputBookFocus');
			} else {
				$this->alertmessage = 'Santri atau member tidak ditemukan.';
				$this->emit('showModalAlert');
			}
		}
	}
	
	public function addBooks()
	{
		if($this->bid){
			$book = Bookdetail::where('barcode', $this->bid)->where('lended', true)->first();
			if($book){ // jika buku ada
				if(!$this->books->contains('barcode', $this->bid)) {	// jika barcode yang diinput berbeda dari sebelumnya
					$lended = $this->lender->lendings->where('bookdetail_id', $book->id)->first();
					if($lended){
						$book->fines = $this->fines($lended->lended_at);
						if($book->fines > 0) $book->desc = 2; else $book->desc = 1;
						$this->books->push($book);
						// fines
						
					} else {
						$this->emit('showModalAlert');
						$this->alertmessage = 'Buku ini tidak sedang dipinjam oleh ' . $this->lender->name . '.';
					}
					
				}
			} else { // jika buku tidak ada
				$book = Bookdetail::where('barcode', $this->bid)->first();
				if($book){
					if($book->status == 3){
						$this->emit('showModalAlert');
						$this->alertmessage = 'Buku ini telah dilaporkan hilang.';
					} else {
						$this->emit('showModalAlert');
						$this->alertmessage = 'Buku ini tidak sedang dipinjam siapapun.';
					}
				} else {
					$this->emit('showModalAlert');
					$this->alertmessage = 'Barcode buku tidak terdaftar.';
				}
				
			}
		}
		$this->bid = null;
	}
	
	public function store()
	{
		if($this->ismember) $lends = Lendingmember::where('member_id', $this->lender->id)->get();
		else $lends = Lendingstudent::where('student_id', $this->lender->id)->get();
		
		foreach ($lends as $lend) {
			// dd($lend->bookdetail->barcode);
			foreach ($this->books as $book) {
				if($book['barcode'] == $lend->bookdetail->barcode)
				{
					$lend->update([
						'returned_at' => $this->returning_date,
						'dayfine' => $book['desc'] == 2 ? $book['fines'] : null,
						'lostfine' => $book['desc'] == 3 ? $book['fines'] : null,
						]
					);
					$det = Bookdetail::find($book['id'])->update([
						'lended' => false,
						'status' => $book['desc'] == 3 ? 3 : $book['status'],
						]
					);
					$lend->users()->attach(Auth::id(), ['returning' => true]);
				}
			}
		}
		
		
		$this->resetAll();
		$this->emit('showModalSuccess');
		$this->alertmessage = 'Pengembalian buku berhasil diproses.';
	}
	
	private function fines($l)
	{
		$diff = $l->diffInDays($this->returning_date);
		if($diff > 5) {
			return ($diff - 5) * 2000;
		} else {
			return 0;
		}
	}
	
	public function reportMissing(Bookdetail $b)
	{
		if(!$this->books->contains('barcode', $b->barcode)){
			$b->fines = $b->book->price;
			$b->desc = 3;
			$this->books->push($b);
		}
		$this->emit('inputBookFocus');
	}
	
	public function resetAll()
	{
		$this->lender = null;
		$this->books = collect();
		$this->uid = null;
		$this->emit('inputMemberFocus');
	}
	
	public function editBookStatus($id)
	{
		Bookdetail::find($id)->update([
			'status' => $this->condition,
			]
		);
	}
	
}

// private function dayFines($a)
// {
	// 	$diff = $a->diffInDays($this->returning_date);
	// 	if($diff > 0) {
		// 		$this->status = 'Terlambat';
		// 		return $diff * 2000;
		// 	} else {
			// 		$this->status = 'OK';
			// 		return 0;
			// 	}
			// }
			
			// public function fines()
			// {
				// 	$dfines = 0;
				// 	$b = $this->confirm;
				// 	$diff = $b->lended_at->diffInDays($this->returning_date);
				// 	if($diff > 5) {
					// 		$dfines = ($diff - 5) * 2000;
					// 	}
					
					
					// 	$book = [
						// 		'title' => $this->confirm->bookdetail->book->title,
						// 		'lended_at' => $this->confirm->lended_at,
						// 		'returned_at' => $this->returning_date,
						// 		'dayfines' => $dfines,
						// 		'brokenfines' => null,
						// 	];
						// }
						