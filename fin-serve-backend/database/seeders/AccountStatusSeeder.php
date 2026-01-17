<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AccountStatus;

class AccountStatusSeeder extends Seeder
{
    public function run()
    {
        $statuses = [
            ['name' => 'Active'],
            ['name' => 'Closed'],
            ['name' => 'Suspended'],
        ];

        foreach ($statuses as $status) {
            AccountStatus::firstOrCreate($status);
        }
    }
}
