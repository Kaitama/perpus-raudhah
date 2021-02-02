<?php

namespace App\Http\Livewire\Members;

use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use App\Member;

class Read extends Component
{
	use WithPagination;
	
	public $search = '';
	public $searching = false;
	public $idToDelete;
	public $member;
	public $statuses = ['1' => 'Guru Raudhah', '2' => 'Pegawai Raudhah', '3' => 'Lainnya'];
	
	// input field
	public $dataid, $nik, $name, $email, $phone, $birthplace, $birthdate, $gender = '1', $status = '1', $address;
	
	public function render()
	{
		if($this->search){
			$this->searching = true;
			$members = Member::where('nik', 'like', '%' . $this->search . '%')->orWhere('name', 'like', '%' . $this->search . '%')->orderByDesc('created_at')->paginate(15);
			return view('livewire.members.read', ['members' => $members]);
		}
		return view('livewire.members.read', ['members' => Member::orderByDesc('created_at')->paginate(15)]);
	}

	public function create()
	{
		$this->resetInput();
		$this->emit('showModalCreate');
	}

	public function store()
	{
		$this->validate([
			'nik' 	=> 'required|numeric|unique:members',
			'name'	=> 'required',
			'email' => 'required|email|unique:members',
			'phone' => 'required|unique:members',
			'birthplace' => 'required',
			'birthdate' => 'required',
			'address' => 'required',
		], [
			'nik.required'	=> 'NIK tidak boleh kosong.',
			'nik.numeric'		=> 'NIK hanya terdiri dari angka.',
			'nik.unique'		=> 'NIK sudah terdaftar.'	
			]
		);

		Member::create([
			'nik'	=> $this->nik,
			'name'	=> $this->name,
			'email'	=> $this->email,
			'phone'	=> $this->phone,
			'birthplace'	=> $this->birthplace,
			'birthdate'	=> Carbon::createFromFormat('d/m/Y', $this->birthdate)->format('Y-m-d'),
			'gender'	=> $this->gender == 1 ? true : false,
			'status'	=> $this->status,
			'address'	=> $this->address,
			]
		);
		$this->resetInput();
		session()->flash('success', 'Member berhasil ditambahkan.');
	}

	public function show(Member $m)
	{
		$this->member = $m;
		$this->emit('showModalDetails');
	}
	
	public function edit($id)
	{
		if($id != $this->dataid){
			$m = Member::find($id);
			$this->dataid = $m->id;
			$this->nik = $m->nik;
			$this->name = $m->name;
			$this->email = $m->email;
			$this->phone = $m->phone;
			$this->birthplace = $m->birthplace;
			// $this->birthdate = Carbon::createFromFormat('Y-m-d', $m->birthdate)->format('d/m/Y');
			$this->birthdate = date('d/m/Y', strtotime($m->birthdate));
			$this->gender = $m->gender == true ? '1' : '2';
			$this->status = $m->status;
			$this->address = $m->address;
		}
		$this->emit('showModalEdit');
	}
	
	public function update()
	{
		$this->validate([
			'nik' 	=> 'required|numeric|' . Rule::unique('members')->ignore($this->dataid),
			'name'	=> 'required',
			'email' => 'required|email|' . Rule::unique('members')->ignore($this->dataid),
			'phone' => 'required|' . Rule::unique('members')->ignore($this->dataid),
			'birthplace' => 'required',
			'birthdate' => 'required',
			'address' => 'required',
			]
		);
		Member::find($this->dataid)->update([
			'nik'	=> $this->nik,
			'name'	=> $this->name,
			'email'	=> $this->email,
			'phone'	=> $this->phone,
			'birthplace'	=> $this->birthplace,
			'birthdate'	=> Carbon::createFromFormat('d/m/Y', $this->birthdate)->format('Y-m-d'),
			'gender'	=> $this->gender == 1 ? true : false,
			'status'	=> $this->status,
			'address'	=> $this->address,
			]
		);
		session()->flash('success', 'Data member berhasil diubah.');
	}
	
	public function confirmDelete($id)
	{
		$this->idToDelete = $id;
		$this->name = Member::find($id)->name;
		$this->emit('showModalDelete');
	}
	
	public function destroy($id)
	{
		Member::find($id)->delete();
		session()->flash('success', 'Member berhasil dihapus.');
	}
	
	public function resetSearch()
	{
		$this->search = '';
		$this->searching = false;
	}
	
	public function resetInput()
	{
		$this->dataid = '';
		$this->nik = '';
		$this->name = '';
		$this->email = '';
		$this->phone = '';
		$this->birthplace = '';
		$this->birthdate = '';
		$this->address = '';
		$this->gender = '1';
		$this->status = '1';
	}
	
}