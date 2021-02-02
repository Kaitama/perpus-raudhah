<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Bookdetail;
use App\Catalog;

class Book extends Model
{
	//
	protected $guarded = [];
	protected $dates = ['purchased_at'];

	public function details()
	{
		return $this->hasMany(Bookdetail::class);
	}

	public function catalog()
	{
		return $this->belongsTo(Catalog::class);
	}

}