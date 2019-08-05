<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\User;
use App\Cacciatore;
use Faker\Generator as Faker;

$factory->define(Cacciatore::class, function (Faker $faker) {
    $nome = $faker->firstName;
    $cognome = $faker->lastName;
    return [
         'nome' => $nome,
         'cognome' => $cognome,
         'registro' => $faker->creditCardNumber,
         'data_nascita' => $faker->date($format='d/m/Y'),
         'user_id' => function () use ($nome,$cognome) {
            return factory(App\User::class)->create(['name' => $nome . ' '. $cognome])->id;
        }
    ];
});
