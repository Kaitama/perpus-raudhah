<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Book;
use App\Bookdetail;

class Catalog extends Model
{
	//
	protected $guarded = [];
	
	public function books()
	{
		return $this->hasMany(Book::class);
	}
	
	public function details()
	{
		return $this->hasManyThrough(Bookdetail::class, Book::class);
	}
}