<?php

namespace Database\Seeders;

use App\Models\ApiClient;
use App\Models\User;
use Illuminate\Database\Seeder;

class ApiClientSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@vokatif.test')->first();

        $plainApiKey = 'vokatif_demo_key_2026';

        ApiClient::updateOrCreate(
            ['name' => 'Vokatif Demo Client'],
            [
                'user_id' => $admin?->id,
                'key_hash' => hash('sha256', $plainApiKey),
                'is_active' => true,
            ]
        );
    }
}
