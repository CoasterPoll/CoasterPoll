<?php

namespace ChaseH\Http\Controllers\DataTable;

use ChaseH\Models\User;
use Illuminate\Http\Request;
use ChaseH\Http\Controllers\Controller;

class UserController extends DataTableController
{
    public function builder() {
        return User::query();
    }
}
