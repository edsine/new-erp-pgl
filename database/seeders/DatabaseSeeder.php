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
        $this->call(ProductServiceIncomeExpenseCategorySeeder::class);
        $this->call(BankAccountSeeder::class);
        $this->call(BranchSeeder::class);
        $this->call(DepartmentSeeder::class);
        $this->call(DesignationSeeder::class);
        $this->call(TaxSeeder::class);
        $this->call(SptaskprogressSeeder::class);
    }
}
