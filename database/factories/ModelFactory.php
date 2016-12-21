<?php
/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/
$factory->define(App\Company::class, function (Faker\Generator $faker) {
    return [
        'title'          => $faker->company,
        'official_title' => $faker->company,
    ];
});
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;
    return [
        'name'           => $faker->name,
        'email'          => $faker->safeEmail,
        'password'       => $password ?: $password = '123123',
        'remember_token' => str_random(10),
        'role'           => rand(App\User::ROLE_USER, App\User::ROLE_ADMIN),
        'discount'       => rand(0, 100),
        'barcode'        => str_random()
    ];
});
$factory->define(App\Shop::class, function (Faker\Generator $faker) {
    return [
        'title'      => $faker->sentence(),
        'address'    => $faker->address,
        'company_id' => function () {
            return App\Company::limit(1)->inRandomOrder()->first()->id;
        }
    ];
});
$factory->define(App\Product::class, function (Faker\Generator $faker) {
    return [
        'title'               => $faker->sentence(),
        'vendor_code'         => $faker->sha1,
        'price'               => $faker->randomFloat(2, 0, 999),
        'price_with_discount' => $faker->randomFloat(2, 0, 999),
        'discount'            => $faker->numberBetween(0, 100),
        'trademark'           => $faker->company,
        'weight'              => $faker->randomFloat(2, 0, 999),
        'unit'                => $faker->word,
        'structure'           => $faker->words(4, true),
        'proteins'            => $faker->randomFloat(2, 0, 999),
        'fats'                => $faker->randomFloat(2, 0, 999),
        'carbohydrates'       => $faker->randomFloat(2, 0, 999),
        'calories'            => $faker->randomFloat(2, 0, 999),
        'shop_id'             => function () {
            return App\Shop::limit(1)->inRandomOrder()->first()->id;
        }
    ];
});
$factory->define(App\Campaign::class, function (Faker\Generator $faker) {
    return [
        'title'   => $faker->sentence(),
        'link'    => $faker->url,
        'start'   => $faker->date('Y-m-d', strtotime('+1 month')),
        'finish'  => $faker->date('Y-m-d', strtotime('+1 year')),
        'shop_id' => function () {
            return App\Shop::limit(1)->inRandomOrder()->first()->id;
        },
    ];
});
