<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLendingstudentUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lendingstudent_user', function (Blueprint $table) {
						$table->id();
						$table->foreignId('lendingstudent_id')->constrained();
						$table->foreignId('user_id')->constrained();
						$table->boolean('returning')->default(false);
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
        Schema::dropIfExists('lendingstudent_user');
    }
}