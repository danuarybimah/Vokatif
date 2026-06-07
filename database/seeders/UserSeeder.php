<?php

namespace Database\Seeders;

use App\Models\OrganizerProfile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('slug', 'admin')->first();
        $organizerRole = Role::where('slug', 'organizer')->first();
        $userRole = Role::where('slug', 'user')->first();

        User::updateOrCreate(
            ['email' => 'admin@vokatif.test'],
            [
                'role_id' => $adminRole->id,
                'name' => 'Admin Vokatif',
                'password' => Hash::make('password'),
                'phone' => '081111111111',
            ]
        );

        $organizer = User::updateOrCreate(
            ['email' => 'organizer@vokatif.test'],
            [
                'role_id' => $organizerRole->id,
                'name' => 'Vokatif Organizer',
                'password' => Hash::make('password'),
                'phone' => '082222222222',
            ]
        );

        OrganizerProfile::updateOrCreate(
            ['user_id' => $organizer->id],
            [
                'organization_name' => 'Vokatif Creative Space',
                'slug' => Str::slug('Vokatif Creative Space'),
                'bio' => 'Organizer kreatif yang menghadirkan event teknologi, musik, edukasi, dan komunitas anak muda.',
                'website' => 'https://vokatif.test',
                'social_links' => [
                    'instagram' => '@vokatif.id',
                    'tiktok' => '@vokatif',
                    'linkedin' => 'Vokatif Creative Space',
                ],
                'is_verified' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'user@vokatif.test'],
            [
                'role_id' => $userRole->id,
                'name' => 'Fahrel Demo User',
                'password' => Hash::make('password'),
                'phone' => '083333333333',
            ]
        );
    }
}
