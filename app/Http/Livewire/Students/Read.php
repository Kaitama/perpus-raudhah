<?php

namespace App\Http\Livewire\Students;

use Livewire\Component;
use Livewire\WithPagination;
use App\Student;

class Read extends Component
{
	use WithPagination;

	public $student, $search, $searching, $gen;
	
	public function render()
	{
		$s = '%' . $this->search . '%';
		if ($this->search) {
			$this->searching = true;
			$this->gen = 1;
		} else {
			$this->searching = false;
		}
		if($this->gen == 'L'){
			$students = Student::where('gender', $this->gen)->paginate(25);
		} elseif($this->gen == 'P'){
			$students = Student::where('gender', $this->gen)->paginate(25);
		} else {	
		$students = Student::where('name', 'like', $s)
		->orWhere('stambuk', 'like', $s)
		->paginate(25);
		}
		return view('livewire.students.read', ['students' => $students]);
	}

	public function show(Student $student)
	{
		$this->student = $student;
		$this->emit('showModalDetails');
	}
}