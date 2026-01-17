<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AccountTypes;

class AccountTypeSeeder extends Seeder
{
    public function run()
    {
        $types = [
            [
                'account_name' => 'Savings',
                'description' => 'Standard savings account'
            ],
            [
                'account_name' => 'Current',
                'description' => 'Business current account'
            ],
            [
                'account_name' => 'Fixed Deposit',
                'description' => 'Long term deposit account'
            ],
        ];

        foreach ($types as $type) {
            AccountTypes::firstOrCreate(
                ['account_name' => $type['account_name']],
                ['description' => $type['description']]
            );
        }
    }
}
