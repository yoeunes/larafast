<?php

$factory(Yoeunes\Larafast\Tests\Stubs\Entities\Lesson::class, [
    'title'   => $faker->sentence,
    'subject' => $faker->words(2),
    'active'  => $faker->boolean,
    'user_id' => 'factory:Yoeunes\Larafast\Tests\Stubs\Entities\User'
]);

$factory(Yoeunes\Larafast\Tests\Stubs\Entities\User::class, [
    'name'           => $faker->name,
    'email'          => $faker->unique()->safeEmail,
    'password'       => bcrypt('secret'),
    'remember_token' => str_random(10),
]);

$factory(Spatie\Permission\Models\Role::class, [
    'name'       => $faker->unique()->name,
]);

$factory(Spatie\Permission\Models\Permission::class, [
    'name'       => $faker->unique()->name,
]);
