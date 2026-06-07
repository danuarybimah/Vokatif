<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Event;
use App\Models\TicketType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $organizer = User::whereHas('role', function ($query) {
            $query->where('slug', 'organizer');
        })->first();

        $events = [
            [
                'category' => 'Technology',
                'title' => 'Vokatif Tech Summit 2026',
                'description' => 'Konferensi teknologi modern yang membahas API, AI, cloud, cybersecurity, dan startup digital.',
                'location' => 'Jakarta Convention Center',
                'city' => 'Jakarta',
                'start_at' => Carbon::now()->addDays(14)->setTime(9, 0),
                'end_at' => Carbon::now()->addDays(14)->setTime(17, 0),
                'is_featured' => true,
                'tickets' => [
                    ['name' => 'Early Bird', 'price' => 75000, 'quota' => 100],
                    ['name' => 'Regular Pass', 'price' => 125000, 'quota' => 250],
                    ['name' => 'VIP Experience', 'price' => 250000, 'quota' => 50],
                ],
            ],
            [
                'category' => 'Music',
                'title' => 'Midnight Neon Festival',
                'description' => 'Festival musik malam dengan visual neon, DJ performance, dan food tenant pilihan.',
                'location' => 'Eco Park Ancol',
                'city' => 'Jakarta',
                'start_at' => Carbon::now()->addDays(21)->setTime(18, 0),
                'end_at' => Carbon::now()->addDays(21)->setTime(23, 30),
                'is_featured' => true,
                'tickets' => [
                    ['name' => 'Festival Pass', 'price' => 99000, 'quota' => 300],
                    ['name' => 'VIP Stage View', 'price' => 199000, 'quota' => 80],
                ],
            ],
            [
                'category' => 'Business',
                'title' => 'Founder Growth Class',
                'description' => 'Kelas intensif untuk membangun bisnis digital, validasi ide, branding, dan growth strategy.',
                'location' => 'Bandung Creative Hub',
                'city' => 'Bandung',
                'start_at' => Carbon::now()->addDays(10)->setTime(10, 0),
                'end_at' => Carbon::now()->addDays(10)->setTime(15, 0),
                'is_featured' => false,
                'tickets' => [
                    ['name' => 'General Seat', 'price' => 50000, 'quota' => 120],
                    ['name' => 'Mentoring Seat', 'price' => 150000, 'quota' => 30],
                ],
            ],
            [
                'category' => 'Education',
                'title' => 'Laravel API Bootcamp',
                'description' => 'Bootcamp praktis membuat REST API Laravel lengkap dengan JWT, API Key, Postman, dan dokumentasi.',
                'location' => 'Universitas Digital Nusantara',
                'city' => 'Yogyakarta',
                'start_at' => Carbon::now()->addDays(7)->setTime(8, 30),
                'end_at' => Carbon::now()->addDays(7)->setTime(16, 0),
                'is_featured' => true,
                'tickets' => [
                    ['name' => 'Student Pass', 'price' => 35000, 'quota' => 200],
                    ['name' => 'Professional Pass', 'price' => 100000, 'quota' => 100],
                ],
            ],
            [
                'category' => 'Lifestyle',
                'title' => 'Urban Wellness Day',
                'description' => 'Event wellness dengan yoga, talkshow kesehatan mental, journaling class, dan mindful networking.',
                'location' => 'Surabaya Wellness Garden',
                'city' => 'Surabaya',
                'start_at' => Carbon::now()->addDays(18)->setTime(7, 0),
                'end_at' => Carbon::now()->addDays(18)->setTime(13, 0),
                'is_featured' => false,
                'tickets' => [
                    ['name' => 'Wellness Pass', 'price' => 65000, 'quota' => 150],
                    ['name' => 'Premium Kit Pass', 'price' => 135000, 'quota' => 60],
                ],
            ],
        ];

        foreach ($events as $eventData) {
            $category = Category::where('name', $eventData['category'])->first();

            $event = Event::updateOrCreate(
                ['slug' => Str::slug($eventData['title'])],
                [
                    'category_id' => $category->id,
                    'organizer_id' => $organizer->id,
                    'title' => $eventData['title'],
                    'slug' => Str::slug($eventData['title']),
                    'description' => $eventData['description'],
                    'location' => $eventData['location'],
                    'city' => $eventData['city'],
                    'cover_image' => null,
                    'start_at' => $eventData['start_at'],
                    'end_at' => $eventData['end_at'],
                    'status' => 'published',
                    'is_featured' => $eventData['is_featured'],
                ]
            );

            foreach ($eventData['tickets'] as $ticket) {
                TicketType::updateOrCreate(
                    [
                        'event_id' => $event->id,
                        'name' => $ticket['name'],
                    ],
                    [
                        'description' => 'Tiket ' . $ticket['name'] . ' untuk event ' . $event->title,
                        'price' => $ticket['price'],
                        'quota' => $ticket['quota'],
                        'sold' => 0,
                        'sales_start_at' => Carbon::now()->subDays(2),
                        'sales_end_at' => $event->start_at->copy()->subHours(2),
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
