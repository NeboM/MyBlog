<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Vote;
use Faker\Generator as Faker;

$factory->define(Vote::class, function (Faker $faker) {

    // We need to generate UNIQUE PAIRS for user_id and post_id
    static $combos;
    $combos = $combos ?: [];
    $user_id = $faker->numberBetween($min = 1, $max = 10);
    $post_id = $faker->numberBetween($min = 1, $max = 20);
    // Save all the combinations in array and later if the same combination occurs search for a new one
    while(in_array(array($user_id, $post_id), $combos)) {
        $post_id = $faker->numberBetween($min = 1, $max = 20);
    }
    $combos[] = [$user_id, $post_id];
    return [
        'user_id' => $user_id,
        'post_id' => $post_id,
        'vote' => $faker->boolean(50)
    ];
});
