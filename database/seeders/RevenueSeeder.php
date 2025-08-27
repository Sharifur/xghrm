<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RevenueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $revenues = [
            [
                'client_id' => 1, // Tech Startup Inc
                'service_type' => 'web_development',
                'amount' => 25000,
                'currency' => 'BDT',
                'bdt_amount' => 25000,
                'status' => 'paid',
                'paid_date' => now()->subDays(5),
                'description' => 'E-commerce website development',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5)
            ],
            [
                'client_id' => 2, // E-commerce Store
                'service_type' => 'shopify_app',
                'amount' => 10,
                'currency' => 'USD',
                'bdt_amount' => 1200, // 10 * 120
                'status' => 'paid',
                'paid_date' => now()->subDays(10),
                'description' => 'Monthly app subscription',
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(10)
            ],
            [
                'client_id' => 3, // Local Restaurant
                'service_type' => 'webflow_template',
                'amount' => 5000,
                'currency' => 'BDT',
                'bdt_amount' => 5000,
                'status' => 'pending',
                'expected_date' => now()->addDays(5),
                'invoice_date' => now()->subDays(5),
                'description' => 'Custom restaurant template',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5)
            ],
            [
                'client_id' => 4, // Marketing Agency
                'service_type' => 'consulting',
                'amount' => 8000,
                'currency' => 'BDT',
                'bdt_amount' => 8000,
                'status' => 'paid',
                'paid_date' => now()->subDays(3),
                'description' => 'Digital strategy consulting',
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3)
            ],
            [
                'client_id' => 5, // Individual Consultant
                'service_type' => 'maintenance',
                'amount' => 25,
                'currency' => 'USD',
                'bdt_amount' => 3000, // 25 * 120
                'status' => 'overdue',
                'expected_date' => now()->subDays(10),
                'invoice_date' => now()->subDays(20),
                'description' => 'Website maintenance contract',
                'created_at' => now()->subDays(20),
                'updated_at' => now()->subDays(20)
            ]
        ];

        foreach ($revenues as $revenue) {
            \App\Models\Revenue::create($revenue);
        }
    }
}
