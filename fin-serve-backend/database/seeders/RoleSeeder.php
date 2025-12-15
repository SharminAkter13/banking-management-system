<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'Admin', 'slug' => 'admin'],
            ['name' => 'Branch Manager', 'slug' => 'branch-manager'],
            ['name' => 'Loan Officer', 'slug' => 'loan-officer'],
            ['name' => 'Bank Teller', 'slug' => 'bank-teller'],
            ['name' => 'Customer', 'slug' => 'customer'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['slug' => $role['slug']],
                ['name' => $role['name']]
            );
        }
    }
}
