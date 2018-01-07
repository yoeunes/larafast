<?php

$factory(Yoeunes\Larafast\Tests\Stubs\Entities\Lesson::class, [
    'title'   => $faker->sentence,
    'subject' => $faker->words(2),
    'active'  => $faker->boolean,
]);
