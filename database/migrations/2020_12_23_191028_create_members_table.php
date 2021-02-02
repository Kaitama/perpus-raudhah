<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
						$table->id();
						$table->string('nik')->unique();
						$table->string('email');
						$table->string('name');
						$table->string('birthplace');
						$table->date('birthdate');
						$table->boolean('gender')->default(true);
						$table->string('phone');
						$table->text('address');
						$table->string('photo')->nullable();
						$table->integer('status')->default(3);
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
        Schema::dropIfExists('members');
    }
}