<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Classroom;
use App\Dormroom;
use App\Studentprofile;
use App\Lendingstudent;

class Student extends Model
{
	protected $dates = ['birthdate'];
	//
	public function classroom()
	{
		return $this->belongsTo(Classroom::class);
	}
	
	// 
	public function dormroom()
	{
		return $this->belongsTo(Dormroom::class);
	}
	
	// 
	public function profile()
	{
		return $this->hasOne(Studentprofile::class);
	}
	
	// 
	public function lendings()
	{
		return $this->hasMany(Lendingstudent::class);
	}
}