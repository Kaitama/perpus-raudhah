<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookdetailsTable extends Migration
{
	/**
	* Run the migrations.
	*
	* @return void
	*/
	public function up()
	{
		Schema::create('bookdetails', function (Blueprint $table) {
			$table->id();
			$table->foreignId('book_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
			$table->string('barcode')->unique();
			$table->integer('status')->default(1);
			$table->timestamps();
		});
	}
	
	/**
	* Reverse the migrations.
	*
	* @return void
	*/
	public function down()
	{
		Schema::dropIfExists('bookdetails');
	}
}