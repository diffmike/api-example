<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('companies')->delete();
        DB::table('users')->where('id', '>', 4)->delete();
        DB::table('products')->delete();
        DB::table('shops')->delete();
        DB::table('campaigns')->delete();
        
        $this->call(CompanySeeder::class);
        $this->call(UserSeeder::class);
        $this->call(CampaignSeeder::class);
    }
}
