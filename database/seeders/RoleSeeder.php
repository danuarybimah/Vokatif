<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'description' => 'Mengelola seluruh sistem Vokatif.',
            ],
            [
                'name' => 'Organizer',
                'slug' => 'organizer',
                'description' => 'Membuat event, mengelola tiket, dan melakukan check-in peserta.',
            ],
            [
                'name' => 'User',
                'slug' => 'user',
                'description' => 'Membeli tiket dan mengikuti event.',
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['slug' => $role['slug']],
                $role
            );
        }
    }
}
