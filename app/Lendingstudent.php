<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Student;
use App\Bookdetail;
use App\User;

class Lendingstudent extends Model
{
	//
	protected $guarded = [];
	protected $dates = ['lended_at', 'returned_at'];
	
	public function student()
	{
		return $this->belongsTo(Student::class);
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