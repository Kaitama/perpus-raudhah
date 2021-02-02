<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Book;
use App\Lendingstudent;
use App\Lendingmember;

class Bookdetail extends Model
{
	//
	protected $guarded = [];

	public function book()
	{
		return $this->belongsTo(Book::class);
	}

	public function lendings()
	{
		return $this->hasMany(Lendingstudent::class);
	}

	public function lendingm()
	{
		return $this->hasMany(Lendingmember::class);
	}
}