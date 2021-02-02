<?php

namespace App\Http\Livewire\Frontpage;

use Livewire\Component;
use App\Book;
use App\Bookdetail;
use App\Member;
class Statistics extends Component
{
    public function render()
    {
        return view('livewire.frontpage.statistics', ['books' => Book::all(), 'bookdetails' => Bookdetail::all(), 'members' => Member::all()]);
    }
}