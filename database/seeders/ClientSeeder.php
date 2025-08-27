<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = [
            [
                'name' => 'Tech Startup Inc',
                'email' => 'contact@techstartup.com',
                'phone' => '+1-555-0101',
                'payment_terms' => 'net_30',
                'notes' => 'Early stage tech startup, fast growing',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'E-commerce Store',
                'email' => 'owner@ecomstore.com',
                'phone' => '+1-555-0102',
                'payment_terms' => 'net_15',
                'notes' => 'Online retail business, regular payments',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Local Restaurant',
                'email' => 'manager@restaurant.com',
                'phone' => null,
                'payment_terms' => 'net_30',
                'notes' => 'Local restaurant chain',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Marketing Agency',
                'email' => 'hello@agency.com',
                'phone' => '+1-555-0104',
                'payment_terms' => 'net_30',
                'notes' => 'Digital marketing agency, high volume projects',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Individual Consultant',
                'email' => null,
                'phone' => '+1-555-0105',
                'payment_terms' => 'immediate',
                'notes' => 'Freelance business consultant, prefers immediate payment',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        foreach ($clients as $client) {
            \App\Models\Client::create($client);
        }
    }
}
