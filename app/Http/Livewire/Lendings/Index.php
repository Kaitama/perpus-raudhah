<?php

namespace App\Http\Livewire\Lendings;

use Auth;

use Carbon\Carbon;
use Livewire\Component;
use App\Bookdetail;
use App\Student;
use App\Member;
use App\Lendingstudent;
use App\Lendingmember;

class Index extends Component
{
	
	public $uid, $lender, $ismember = true, $lended = 0, $alertmessage;
	public $bid, $books;
	
	public function mount()
	{
		$this->books = collect();
	}
	public function render()
	{
		return view('livewire.lendings.index');
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
				if($this->lended < 3) $this->emit('inputBookFocus');
			} else {
				// session()->flash('notfound', 'Santri atau member tidak ditemukan.');
				$this->alertmessage = 'Santri atau member tidak ditemukan.';
				$this->emit('showModalAlert');
			}
		}
	}
	
	public function addBooks()
	{
		if($this->bid){ // jika ada input barcode
			$book = Bookdetail::where('barcode', $this->bid)->first(); // cari buku
			if($book){ // jika buku ada
				if($book->book->lendable){	// jika buku bisa dipinjamkan
					if(!$this->books->contains('barcode', $this->bid)) {	// jika barcode yang diinput berbeda dari sebelumnya
						if($this->books->count() + $this->lended < 3){	// jika buku yang dipinjam tidak lebih dari 3
							if($book->status < 3) {	// jika status buku tersedia atau rusak
								if (!$book->lended) {	// jika buku belum dipinjam siapapun
									$this->books->push($book);	// tambahkan buku kedalam list
								} else {	// jika buku sedang dipinjam
									$this->emit('showModalAlert');
									$this->alertmessage = 'Buku ini sedang dipinjamkan.';
								}
							} else{	// jika status buku hilang
								$this->emit('showModalAlert');
								$this->alertmessage = 'Buku ini tidak dapat dipinjamkan karena berstatus <b>Hilang</b>.';
							}
						} else {	// jika buku yang dipinjam lebih dari 3
							$this->emit('showModalAlert');
							$this->alertmessage = 'Tidak dapat meminjam buku lebih dari 3.';
						}
					}
				} else {	// jika buku tidak bisa dipinjamkan
					$this->emit('showModalAlert');
					$this->alertmessage = 'Buku ini tidak dapat dipinjamkan.';
				}
			} else { // jika buku tidak ada
				$this->emit('showModalAlert');
				$this->alertmessage = 'Buku tidak terdaftar.';
			}
			$this->bid = null;
			$book = null;
		}
	}
	
	public function remove($key)
	{
		$this->books->forget($key);
		$this->emit('inputBookFocus');
	}
	
	public function resetAll()
	{
		$this->lender = null;
		$this->books = collect();
		$this->uid = null;
		$this->emit('inputMemberFocus');
	}
	
	public function store()
	{
		if($this->ismember) {
			foreach ($this->books as $book) {
				Bookdetail::find($book['id'])->update([
					'lended' => true,
					]
				);
				$lend = Lendingmember::create([
					'member_id' => $this->lender->id,
					'bookdetail_id' => $book['id'],
					'lended_at'	=> Carbon::now(),
					]
				);
				$lend->users()->attach(Auth::id());
			}
		} else {
			foreach ($this->books as $book) {
				Bookdetail::find($book['id'])->update([
					'lended' => true,
					]
				);
				$lend = Lendingstudent::create([
					'student_id' => $this->lender->id,
					'bookdetail_id' => $book['id'],
					'lended_at'	=> Carbon::now(),
					]
				);
				$lend->users()->attach(Auth::id());
			}
		}
		
		
		$this->resetAll();
		$this->emit('showModalSuccess');
		$this->alertmessage = 'Peminjaman buku berhasil diproses.';
	}
	
	
}