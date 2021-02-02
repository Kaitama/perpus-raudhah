<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLendingmemberUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lendingmember_user', function (Blueprint $table) {
						$table->id();
						$table->foreignId('lendingmember_id')->constrained();
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
        Schema::dropIfExists('lendingmember_user');
    }
}