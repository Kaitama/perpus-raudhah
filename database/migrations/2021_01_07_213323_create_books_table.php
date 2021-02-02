<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBooksTable extends Migration
{
	/**
	* Run the migrations.
	*
	* @return void
	*/
	public function up()
	{
		Schema::create('books', function (Blueprint $table) {
			$table->id();
			$table->foreignId('catalog_id')->nullable()->constrained()->onDelete('set null')->cascadeOnUpdate();
			$table->string('title');
			$table->string('author');
			$table->string('year');
			$table->string('publisher')->nullable();
			$table->string('source')->nullable();
			$table->integer('price')->nullable();
			$table->timestamp('purchased_at')->nullable();
			$table->text('description')->nullable();
			$table->boolean('lendable')->default(true);
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
		Schema::dropIfExists('books');
	}
}