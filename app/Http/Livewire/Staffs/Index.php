<?php

namespace App\Http\Livewire\Staffs;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;
use App\User;
use App\Userprofile;
use Cache;


class Index extends Component
{
	use WithPagination;
	
	public $n = 1, $search, $searching = false, $editing = false, $idtoedit, $idtodelete;
	public $name, $role, $email, $username;
	
	public function render()
	{
		$s = '%' . $this->search . '%';
		if ($this->search) {
			$this->searching = true;
		} else {
			$this->searching = false;
		}

		$users = User::where('name', 'like', $s)
		->orWhere('email', 'like', $s)
		->orWhere('username', 'like', $s)
		->latest()
		->role(['admin perpus', 'staff perpus'])
		->paginate(25);

		foreach ($users as $user) {
			if(Cache::has('online-' . $user->id)){
				$user->status = true;
			} else {
				$user->status = false;
			}
		}

		$roles = Role::whereIn('name', ['admin perpus', 'staff perpus'])->get();
		return view('livewire.staffs.index', ['users' => $users, 'roles' => $roles]);
	}

	public function resetSearch()
	{
		$this->search = '';
		$this->searching = false;
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
			'name'	=> 'required',
			'role'	=> 'required',
			'username'	=> 'required|unique:users',
			'email'	=> 'required|email|unique:users',
			]
		);
		
		$user = User::create([
			'level' => 5,
			'name'	=> $this->name,
			'username' => $this->username,
			'email'	=> $this->email,
			'password' => Hash::make('password'),
			]
		);
		
		Userprofile::create(['user_id' => $user->id]);
		
		$user->syncRoles($this->role);
		
		session()->flash('success', 'Staff baru berhasil ditambahkan.');
		$this->resetInput();
		
	}
	
	public function edit(User $user)
	{
		if(!$this->editing) 
		{
			$this->editing = true;
		}
		if($this->idtoedit != $user->id)
		{
			$this->idtoedit = $user->id;
			$this->name = $user->name;
			$this->username = $user->username;
			$this->email = $user->email;
			$this->role = $user->getRoleNames()->first();
		}
		$this->emit('showModalEdit');
	}
	
	public function update()
	{
		$this->validate([
			'name'	=> 'required',
			'role'	=> 'required',
			'username'	=> 'required|unique:users,username,' . $this->idtoedit,
			'email'	=> 'required|email|unique:users,email,' . $this->idtoedit,
			]
		);
		
		$user = User::find($this->idtoedit);
		$user->update([
			'name' => $this->name,
			'username'	=> $this->username,
			'email'	=> $this->email,
			]
		);
		$user->syncRoles($this->role);
		session()->flash('success', 'Data staff berhasil diubah.');
		$this->resetInput();
		
	}
	
	public function confirmDelete(User $user)
	{
		$this->idtodelete = $user;
		$this->emit('showModalDelete');
	}

	public function resetPassword()
	{
		User::find($this->idtoedit)->update([
			'password' => Hash::make('password'),
		]);
		$this->idtoedit = null;
		$this->resetInput();
		session()->flash('success', 'Password staff berhasil direset.');
	}
	
	public function destroy()
	{
		$user = $this->idtodelete;
		$user->delete();
		$this->idtodelete = null;
		session()->flash('success', 'Data staff berhasil dihapus.');
	}
	
	public function resetInput()
	{
		$this->name = null;
		$this->role = null;
		$this->email = null;
		$this->username = null;
	}
}