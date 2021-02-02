<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Member;
use Faker\Generator as Faker;

$factory->define(Member::class, function (Faker $faker) {
    return [
				//
				'nik' => $faker->unique()->randomNumber(9),
				'email'	=> $faker->unique()->safeEmail,
				'name'	=> $faker->name,
				'birthdate'	=> $faker->date,
				'birthplace' => $faker->city,
				'gender'	=> $faker->numberBetween(0,1),
				'phone'	=> $faker->e164PhoneNumber,
				'address' => $faker->address,
				'status'	=> $faker->numberBetween(1, 3),
    ];
});