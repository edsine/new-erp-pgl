<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(ChartOfAccountTypeTableSeeder::class);
        $this->call(ChartOfAccountSubTypeTableSeeder::class);
        $this->call(ChartOfAccountSubTypeLevel2TableSeeder::class);
        $this->call(ChartOfAccountSubTypeLevel3TableSeeder::class);
        $this->call(ChartOfAccountTableSeeder::class);
    }
}
