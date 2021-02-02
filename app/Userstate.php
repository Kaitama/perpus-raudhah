<?php

namespace App;
use App\User;

use Illuminate\Database\Eloquent\Model;

class Userstate extends Model
{
		//
		protected $dates = ['lastseen_at', 'loggedout_at'];
		protected $guarded = [];

		public function user()
		{
			return $this->belongsTo(User::class);
		}
}