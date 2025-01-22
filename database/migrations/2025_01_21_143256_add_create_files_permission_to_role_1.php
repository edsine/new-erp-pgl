<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create the "create files" permission
        $permission = Permission::create(['name' => 'create files']);

        // Assign the "create files" permission to the role with id = 1
        $role = Role::find(1);  // Assuming role_id = 1
        if ($role) {
            $role->givePermissionTo($permission);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Rollback the changes (delete permission and remove it from the role)
        $role = Role::find(1);
        if ($role) {
            $role->revokePermissionTo('create files');
        }

        // Delete the "create files" permission
        $permission = Permission::where('name', 'create files')->first();
        if ($permission) {
            $permission->delete();
        }
    }
};
