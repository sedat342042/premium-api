<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class SuperUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'first_name' => 'Super', 
            'last_name' => 'User', 
            'email' => 'superuser@gmail.com',
            'password' => 'superuser123', 
            'email_verified_at' => date("Y-m-d H:i:s"),
        ]);
    
        $role = Role::create(['name' => 'superuser']);     
        $user->assignRole([$role->id]);

        /*$userRole = Role::create(['name' => 'user']);   
        $userRole->syncPermissions('dashboard');*/
    }
}