<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BookTemplateExport;
use App\Exports\ReportLendingsExport;
use App\Exports\BookExport;
use App\Imports\BookImport;
use App\Http\Controllers\BarcodeController;
use App\Student;
use Carbon\Carbon;
// use PDF;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
	return view('frontpage.index');
});

Auth::routes(['register' => false]);
// Route::view('/dashboard', 'dashboard.index')->name('dashboard.index');
Route::prefix('dashboard')->middleware('auth')->group(function () {
	Route::group(['middleware' => ['role_or_permission:developer|m perpus']], function () {
		// dashboard
		Route::view('/', 'dashboard.index')->name('dashboard.index');

		// staffs
		Route::view('/staffs', 'dashboard.staffs')->name('staffs.index')->middleware('role_or_permission:developer|admin perpus');
		
		// lendings
		Route::view('/lendings', 'dashboard.lendings')->name('lendings.index');

		// returnings
		Route::view('/returnings', 'dashboard.returnings')->name('returnings.index');

		// students
		Route::view('/students', 'dashboard.students')->name('students.index');
		Route::get('/students/libpass/{id}', function($id){
			$now = Carbon::now();
			$student = Student::find($id);
			if($student->lendings->where('returned_at', null)->count() > 0) return abort(403);
			$pdf = PDF::loadView('dashboard.libpass', ['student' => $student, 'now' => $now]);
			return $pdf->stream('SURAT BEBAS PERPUSTAKAAN');
		})->name('students.libpass');

		// members
		Route::view('/members', 'dashboard.members')->name('members.index');
		Route::view('/members/show/{dataid}', 'dashboard.members-show')->name('members.show');

		// catalogs
		Route::view('/catalogs', 'dashboard.catalogs')->name('catalogs.index');

		// books
		Route::view('/books', 'dashboard.books')->name('books.index');
		Route::get('/books/template', function(){
			return Excel::download(new BookTemplateExport, 'TEMPLATE_BUKU_' . time() . '.xlsx');
		})->name('books.template');
		Route::post('/books/import', function(Request $request){
			Excel::import(new BookImport, $request->file('excel'));
			return back()->with('success', 'Data buku berhasil diupload.');
		})->name('books.import');
		Route::get('/books/export', function(){
			return Excel::download(new BookExport, 'DATA_BUKU_' . date('d') . '_' . date('m') . '_' . date('Y') . '.xlsx');
		})->name('books.export');
		Route::get('/books/barcode/{id}', [BarcodeController::class, 'download'])->name('books.barcode');
		
		// report
		// lendings
		Route::view('/report/lendings', 'dashboard.reportlendings')->name('report.lendings')->middleware('role_or_permission:developer|admin perpus');;
		Route::get('/report/lendings/download/{s}/{e}/{m}', function($s, $e, $m){
			return Excel::download(new ReportLendingsExport($s, $e, $m), 'LAPORAN_PEMINJAMAN_' . time() . '.xlsx');
		})->name('download.report.lendings')->middleware('role_or_permission:developer|admin perpus');;
	});

});