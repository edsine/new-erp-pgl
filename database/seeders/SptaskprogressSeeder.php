<?php

namespace Database\Seeders;

use App\Models\Sptaskprogress;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SptaskprogressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $progress= [
            [

              'name'=>  'To DO',

            ],
            [

              'name'=>  'In Progress',


            ],
            [

              'name'=>  'Review',



            ],
            [

             'name'=>   'Done'



            ],


        ];
        Sptaskprogress::insert($progress);
    }
}
