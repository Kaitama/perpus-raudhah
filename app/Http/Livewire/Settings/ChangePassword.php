<?php

namespace App\Http\Livewire\Settings;
use Illuminate\Support\Facades\Hash;

use Livewire\Component;
use App\User;
use Auth;

class ChangePassword extends Component
{
	public $old_password, $new_password, $new_password_confirmation;
	public function render()
	{
		return view('livewire.settings.change-password');
	}
	
	public function changepass()
	{
		$this->validate([
			'old_password'	=> 'required',
			'new_password'	=> 'required|min:6|confirmed',
			'new_password_confirmation'	=> 'required',
			]
		);
		
		$user = User::find(Auth::id());
		
		if(Hash::check($this->old_password, $user->password)){
			$user->update([
				'password'	=> Hash::make($this->new_password),
				]
			);
		}

		session()->flash('error', 'Password lama anda salah!');
		return redirect()->to('/dashboard');
	}
}