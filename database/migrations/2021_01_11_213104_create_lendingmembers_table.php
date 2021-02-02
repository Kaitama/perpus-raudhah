<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLendingmembersTable extends Migration
{
	/**
	* Run the migrations.
	*
	* @return void
	*/
	public function up()
	{
		Schema::create('lendingmembers', function (Blueprint $table) {
			$table->id();
			$table->foreignId('member_id')->nullable()->constrained()->cascadeOnUpdate()->cascadeOnDelete();
			$table->foreignId('bookdetail_id')->nullable()->constrained()->cascadeOnUpdate()->cascadeOnDelete();
			$table->timestamp('lended_at');
			$table->timestamp('returned_at')->nullable();
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
		Schema::dropIfExists('lendingmembers');
	}
}