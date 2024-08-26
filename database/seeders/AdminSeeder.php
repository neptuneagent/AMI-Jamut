<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'jamut']);
        Role::create(['name' => 'prodi']);
        Role::create(['name' => 'gkm']);
        Role::create(['name' => 'auditor']);

        $admin = User::create([
            'name' => 'adminjamut',
            'email' => 'adminjamut@bssn.go.id',
            'password' => bcrypt(env('DEFAULT_USER_PASSWORD', 'default_password')),
        ]);

        // Assign admin role to the default admin user
        $adminRole = Role::where('name', 'adminjamut')->first();
        $admin->assignRole($adminRole);
    }
}
