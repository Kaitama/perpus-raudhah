<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Member;
use App\Bookdetail;
use App\User;

class Lendingmember extends Model
{
	//
	protected $guarded = [];
	protected $dates = ['lended_at', 'returned_at'];
	
	public function member()
	{
		return $this->belongsTo(Member::class);
	}
	
	public function bookdetail()
	{
		return $this->belongsTo(Bookdetail::class);
	}

	public function users()
	{
		return $this->belongsToMany(User::class)
		->withPivot('returning')
		->withTimestamps();
	}

}