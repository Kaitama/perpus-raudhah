<?php

namespace App\Http\Livewire\Dashboard;

use Livewire\Component;
use App\Book;
use App\Bookdetail;
use App\Member;
use App\Student;
use App\Lendingmember;
use App\Lendingstudent;
use Carbon\Carbon;
use Auth;

class Index extends Component
{
	public function render()
	{
		$user = Auth::user();
		$students = Student::where('status', 1)->get();
		$members = Member::all();
		$books = Book::all();
		$bookdetails = Bookdetail::all();
		$lmembers = Lendingmember::whereDate('lended_at', Carbon::today())->get();
		$lstudents = Lendingstudent::whereDate('lended_at', Carbon::today())->get();
		$rmembers = Lendingmember::whereDate('returned_at', Carbon::today())->get();
		$rstudents = Lendingstudent::whereDate('returned_at', Carbon::today())->get();
		return view('livewire.dashboard.index', [
			'user'	=> $user,
			'students' => $students,
			'members'	=> $members,
			'books'	=> $books,
			'bookdetails'	=> $bookdetails,
			'lmembers'	=> $lmembers,
			'lstudents' => $lstudents,
			'rmembers'	=> $rmembers,
			'rstudents' => $rstudents,
			]
		);
	}
}