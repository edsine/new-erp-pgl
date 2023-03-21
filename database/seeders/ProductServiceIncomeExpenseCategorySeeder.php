<?php

namespace Database\Seeders;

use App\Models\ProductServiceCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductServiceIncomeExpenseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $expenses = [

            //Admin Expenses

            ['type' => 2, 'color' => 'FFFFFF', 'name' => 'Airtime', 'created_by' => 1],
            ['type' => 2, 'color' => 'FFFFFF', 'name' => 'Award Night', 'created_by' => 1],
            ['type' => 2, 'color' => 'FFFFFF', 'name' => 'Bank Charges', 'created_by' => 1],
            ['type' => 2, 'color' => 'FFFFFF', 'name' => 'CEO Expenses', 'created_by' => 1],
            ['type' => 2, 'color' => 'FFFFFF', 'name' => 'CEO House Expenses', 'created_by' => 1],
            ['type' => 2, 'color' => 'FFFFFF', 'name' => 'Cleaning Services', 'created_by' => 1],
            ['type' => 2, 'color' => 'FFFFFF', 'name' => 'Compliance Documents', 'created_by' => 1],
            ['type' => 2, 'color' => 'FFFFFF', 'name' => 'Consultancy Fee', 'created_by' => 1],
            ['type' => 2, 'color' => 'FFFFFF', 'name' => 'Consumables', 'created_by' => 1],
            ['type' => 2, 'color' => 'FFFFFF', 'name' => 'Corporate Social Responsibility', 'created_by' => 1],
            ['type' => 2, 'color' => 'FFFFFF', 'name' => 'Diesel', 'created_by' => 1],
            ['type' => 2, 'color' => 'FFFFFF', 'name' => 'Domain Hosting', 'created_by' => 1],
            ['type' => 2, 'color' => 'FFFFFF', 'name' => 'Fueling', 'created_by' => 1],
            ['type' => 2, 'color' => 'FFFFFF', 'name' => 'General Office Expense', 'created_by' => 1],
            ['type' => 2, 'color' => 'FFFFFF', 'name' => 'HONORARIUM', 'created_by' => 1],
            ['type' => 2, 'color' => 'FFFFFF', 'name' => 'Legal Fee', 'created_by' => 1],
            ['type' => 2, 'color' => 'FFFFFF', 'name' => 'Loan', 'created_by' => 1],
            ['type' => 2, 'color' => 'FFFFFF', 'name' => 'Logistics', 'created_by' => 1],
            ['type' => 2, 'color' => 'FFFFFF', 'name' => 'Medical Bill', 'created_by' => 1],
            ['type' => 2, 'color' => 'FFFFFF', 'name' => 'Office Maintenance', 'created_by' => 1],
            ['type' => 2, 'color' => 'FFFFFF', 'name' => 'Property Plant and Equipment', 'created_by' => 1],
            ['type' => 2, 'color' => 'FFFFFF', 'name' => 'Public Relation (PR)', 'created_by' => 1],
            ['type' => 2, 'color' => 'FFFFFF', 'name' => 'Prepaid', 'created_by' => 1],
            ['type' => 2, 'color' => 'FFFFFF', 'name' => 'Printer Maintenance', 'created_by' => 1],
            ['type' => 2, 'color' => 'FFFFFF', 'name' => 'Printing', 'created_by' => 1],
            ['type' => 2, 'color' => 'FFFFFF', 'name' => 'ROI Paid', 'created_by' => 1],
            ['type' => 2, 'color' => 'FFFFFF', 'name' => 'Salary', 'created_by' => 1],
            ['type' => 2, 'color' => 'FFFFFF', 'name' => 'Staff House Expenses', 'created_by' => 1],
            ['type' => 2, 'color' => 'FFFFFF', 'name' => 'Staff Welfare', 'created_by' => 1],
            ['type' => 2, 'color' => 'FFFFFF', 'name' => 'Stationaries', 'created_by' => 1],
            ['type' => 2, 'color' => 'FFFFFF', 'name' => 'Subscription and Licenses', 'created_by' => 1],
            ['type' => 2, 'color' => 'FFFFFF', 'name' => 'Tender', 'created_by' => 1],
            ['type' => 2, 'color' => 'FFFFFF', 'name' => 'Transportation Expenses', 'created_by' => 1],
            ['type' => 2, 'color' => 'FFFFFF', 'name' => 'Vehicle Maintenance', 'created_by' => 1],
            ['type' => 2, 'color' => 'FFFFFF', 'name' => 'Xmas Package', 'created_by' => 1],
            ['type' => 2, 'color' => 'FFFFFF', 'name' => 'Other Expenses', 'created_by' => 1],

            // Project Expenses
            ['type' => 2, 'color' => 'FFFFFF', 'name' => 'Direct Cost', 'created_by' => 1],
            ['type' => 2, 'color' => 'FFFFFF', 'name' => 'Logistics', 'created_by' => 1],
            ['type' => 2, 'color' => 'FFFFFF', 'name' => 'PR', 'created_by' => 1],

            // Income
            ['type' => 1, 'color' => 'FFFFFF', 'name' => 'Project Income', 'created_by' => 1],
            ['type' => 1, 'color' => 'FFFFFF', 'name' => 'Discounts Allowed', 'created_by' => 1],
            ['type' => 1, 'color' => 'FFFFFF', 'name' => 'Others', 'created_by' => 1],

            // Product and Services
            ['type' => 0, 'color' => 'FFFFFF', 'name' => 'ICT', 'created_by' => 1],
            ['type' => 0, 'color' => 'FFFFFF', 'name' => 'Furnitures & Fittings', 'created_by' => 1],
            ['type' => 0, 'color' => 'FFFFFF', 'name' => 'Stationaries', 'created_by' => 1],
            ['type' => 0, 'color' => 'FFFFFF', 'name' => 'Facilities', 'created_by' => 1],
            ['type' => 0, 'color' => 'FFFFFF', 'name' => 'Others', 'created_by' => 1],
        ];

        ProductServiceCategory::insert($expenses);
    }
}
