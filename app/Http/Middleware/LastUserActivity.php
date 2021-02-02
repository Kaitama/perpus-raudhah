<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use App\Userstate;
use Auth;
use Cache;
use Carbon\Carbon;

class LastUserActivity
{
	/**
	* Handle an incoming request.
	*
	* @param  \Illuminate\Http\Request  $request
	* @param  \Closure  $next
	* @return mixed
	*/
	public function handle($request, Closure $next)
	{
		if (Auth::check()) {
			$expiresAt = Carbon::now()->addMinutes(5);
			Cache::put('online-' . Auth::user()->id, true, $expiresAt);
			
			// last seen
			$usr = User::find(Auth::id());
			if($usr->state) {
				$usr->state->update(['lastseen_at' => Carbon::now()]);
			}
			else {$usr->state()->create([
				'user_id' => Auth::id(),
				'lastseen_at' => Carbon::now(),
				]
			);}
		}
		return $next($request);
	}
}