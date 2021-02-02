<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToLendingstudentsTable extends Migration
{
	/**
	* Run the migrations.
	*
	* @return void
	*/
	public function up()
	{
		Schema::table('lendingstudents', function (Blueprint $table) {
			//
			$table->integer('dayfine')->nullable();
			$table->integer('lostfine')->nullable();
			$table->integer('brokenfine')->nullable();
			$table->text('description')->nullable();
		});
	}
	
	/**
	* Reverse the migrations.
	*
	* @return void
	*/
	public function down()
	{
		Schema::table('lendingstudents', function (Blueprint $table) {
			//
		});
	}
}