<?php

namespace ChaseH\Console\Commands;

use ChaseH\Models\Role;
use ChaseH\Models\User;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserPromotion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:promote {username : The username of the user to promote}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Promotes an existing user to an admin.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $username = $this->argument('username');
        try {
            $user = User::where('name', $username)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            $this->error("Cannot find {$username}.");
            die();
        }

        // Check whether we're using the "Admin" role,
        // Prompt for name of Role if not
        // Validate to make sure that's actually a valid role
        if(Role::where('name', "Admin")->count() > 0) {
            $role = "Admin";
        } else {
            $valid = false;
            while(!$valid) {
                $input = $this->ask("What role should we promote {$username} to?", "Admin");
                if(Role::where('name', $input)->count() > 0) {
                    $valid = true;
                    $role = $input;
                } else {
                    $this->error("Role \"{$input}\" couldn't be found.");
                }
            }
        }

        $user->promoteToAdmin($role);

        $this->info("Done!");
    }
}
