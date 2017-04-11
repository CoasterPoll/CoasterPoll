<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCoreUserPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Illuminate\Support\Facades\DB::table('permissions')->insert([
            ['name' => "Can manage users", 'group' => 'Users', 'description' => "The ability to add/remove/edit users accounts."],
            ['name' => "Can manage roles", 'group' => 'Users', 'description' => "The ability to add/remove/edit user roles."],
        ]);

        \Illuminate\Support\Facades\DB::table('roles')->insert([
            ['name' => "Admin"],
            ['name' => "User"]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
