<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'System Admin',
                'email' => 'admin@finserve.com',
                'phone' => '0700000001',
                'role' => 'admin',
            ],
            [
                'name' => 'Branch Manager',
                'email' => 'manager@finserve.com',
                'phone' => '0700000002',
                'role' => 'branch-manager',
            ],
            [
                'name' => 'Loan Officer',
                'email' => 'officer@finserve.com',
                'phone' => '0700000003',
                'role' => 'loan-officer',
            ],
            [
                'name' => 'Bank Teller',
                'email' => 'teller@finserve.com',
                'phone' => '0700000004',
                'role' => 'bank-teller',
            ],
            [
                'name' => 'Customer User',
                'email' => 'customer@finserve.com',
                'phone' => '0700000005',
                'role' => 'customer',
            ],
        ];

        foreach ($users as $data) {
            $role = Role::where('slug', $data['role'])->first();

            if (!$role) {
                continue;
            }

            User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'role_id' => $role->id,
                    'name' => $data['name'],
                    'phone' => $data['phone'],
                    'password' => Hash::make('12345678'),
                    'status' => 'active',
                ]
            );
        }
    }
}
