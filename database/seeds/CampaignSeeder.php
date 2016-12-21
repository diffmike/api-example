<?php

use Illuminate\Database\Seeder;

class CampaignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Campaign::class, 15)->create()
            ->each(function (App\Campaign $campaign) {
                $campaign->products()->saveMany(factory(App\Product::class, 5)->make());
            });
    }
}
