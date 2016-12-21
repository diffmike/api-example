<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\User::class, 10)
            ->create(['role' => App\User::ROLE_MANAGER])
            ->each(function (App\User $user) {
                $user->shops()->saveMany(
                    App\Shop::limit(3)->inRandomOrder()->get()
                );
            });
        
        factory(App\User::class, 30)->create(['role' => App\User::ROLE_USER]);
    }
}
