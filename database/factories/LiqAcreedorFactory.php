<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Liq_Acreedor;
use Faker\Generator as Faker;

$factory->define(Liq_Acreedor::class, function (Faker $faker) {
    return [
        'nombre' => $faker->name,
    ];
});
