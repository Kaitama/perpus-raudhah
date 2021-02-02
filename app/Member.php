<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Lendingmember;

class Member extends Model
{
	//
	protected $guarded = [];
	protected $dates = ['birthdate'];
	public function lendings()
	{
		return $this->hasMany(Lendingmember::class);
	}
}