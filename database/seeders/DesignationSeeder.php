<?php

namespace Database\Seeders;

use App\Models\Designation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DesignationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['department_id' => 1, 'name' => 'Head, LIC', 'created_by' => 1,  'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s'),],
            ['department_id' => 1, 'name' => 'Head, Legal & Compliance', 'created_by' => 1,  'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s'),],
            ['department_id' => 1, 'name' => 'Head, Procurement', 'created_by' => 1,  'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s'),],
            ['department_id' => 1, 'name' => 'Head, HR & IC', 'created_by' => 1,  'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s'),],
            ['department_id' => 1, 'name' => 'Legal & Compliance Officer', 'created_by' => 1,  'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s'),],
            ['department_id' => 1, 'name' => 'Office Admin', 'created_by' => 1,  'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s'),],
            ['department_id' => 1, 'name' => 'Facility Manager', 'created_by' => 1,  'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s'),],
            ['department_id' => 1, 'name' => 'Admin Assistant', 'created_by' => 1,  'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s'),],
            ['department_id' => 1, 'name' => 'LIC Assistant', 'created_by' => 1,  'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s'),],

            ['department_id' => 2, 'name' => 'Head, Growth', 'created_by' => 1,  'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s'),],
            ['department_id' => 2, 'name' => 'Head, PMO', 'created_by' => 1,  'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s'),],
            ['department_id' => 2, 'name' => 'Head, Business Development', 'created_by' => 1,  'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s'),],
            ['department_id' => 2, 'name' => 'Head, Media & Corporate Comm.', 'created_by' => 1,  'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s'),],
            ['department_id' => 2, 'name' => 'Business Development Officer', 'created_by' => 1,  'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s'),],
            ['department_id' => 2, 'name' => 'Project Administrator', 'created_by' => 1,  'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s'),],
            ['department_id' => 2, 'name' => 'Project Manager', 'created_by' => 1,  'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s'),],
            ['department_id' => 2, 'name' => 'Project Coordinator', 'created_by' => 1,  'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s'),],
            ['department_id' => 2, 'name' => 'Sales Representative', 'created_by' => 1,  'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s'),],
            ['department_id' => 2, 'name' => 'Graphics Designer', 'created_by' => 1,  'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s'),],
            ['department_id' => 2, 'name' => 'Growth Intern', 'created_by' => 1,  'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s'),],

            ['department_id' => 3, 'name' => 'Head, ICT', 'created_by' => 1,  'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s'),],
            ['department_id' => 3, 'name' => 'Head, ITP', 'created_by' => 1,  'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s'),],
            ['department_id' => 3, 'name' => 'Head, ITS', 'created_by' => 1,  'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s'),],
            ['department_id' => 3, 'name' => 'Technical Project Manager', 'created_by' => 1,  'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s'),],
            ['department_id' => 3, 'name' => 'Solutions Architect', 'created_by' => 1,  'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s'),],
            ['department_id' => 3, 'name' => 'Fullstack Developer', 'created_by' => 1,  'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s'),],
            ['department_id' => 3, 'name' => 'Frontend Developer', 'created_by' => 1,  'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s'),],
            ['department_id' => 3, 'name' => 'Backend Developer', 'created_by' => 1,  'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s'),],
            ['department_id' => 3, 'name' => 'UI/UX', 'created_by' => 1,  'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s'),],
            ['department_id' => 3, 'name' => 'ITP Intern', 'created_by' => 1,  'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s'),],
            ['department_id' => 3, 'name' => 'Network Administrator', 'created_by' => 1,  'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s'),],
            ['department_id' => 3, 'name' => 'Technical Support', 'created_by' => 1,  'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s'),],

            ['department_id' => 4, 'name' => 'Head, Finance & Account', 'created_by' => 1,  'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s'),],
            ['department_id' => 4, 'name' => 'Head, Finance & Investment', 'created_by' => 1,  'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s'),],
            ['department_id' => 4, 'name' => 'Finance & Project Officer', 'created_by' => 1,  'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s'),],
            ['department_id' => 4, 'name' => 'Finance & Account Assistant', 'created_by' => 1,  'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s'),],
        ];

        Designation::insert($data);
    }
}
