<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExpenseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            // Assets
            [
                'name' => 'Cash',
                'description' => 'Money in bank accounts and cash on hand',
                'type' => 'asset',
                'icon' => 'fas fa-money-bill-wave',
                'color' => '#28a745',
                'is_recurring' => false,
                'default_amount' => 150000.00,
                'currency' => 'BDT',
                'tooltip' => 'All cash available including bank accounts, petty cash, and savings',
                'sort_order' => 1
            ],
            [
                'name' => 'Money from Clients',
                'description' => 'Accounts receivable from customers',
                'type' => 'asset',
                'icon' => 'fas fa-hand-holding-usd',
                'color' => '#28a745',
                'is_recurring' => false,
                'default_amount' => 85000.00,
                'currency' => 'BDT',
                'tooltip' => 'Payments owed to you by customers for services/products delivered',
                'sort_order' => 2
            ],
            [
                'name' => 'Equipment',
                'description' => 'Office equipment and technology',
                'type' => 'asset',
                'icon' => 'fas fa-laptop',
                'color' => '#28a745',
                'is_recurring' => false,
                'default_amount' => 45000.00,
                'currency' => 'BDT',
                'tooltip' => 'Computers, furniture, vehicles, and other business equipment',
                'sort_order' => 3
            ],
            [
                'name' => 'Inventory',
                'description' => 'Stock and products for sale',
                'type' => 'asset',
                'icon' => 'fas fa-boxes',
                'color' => '#28a745',
                'is_recurring' => false,
                'default_amount' => 25000.00,
                'currency' => 'BDT',
                'tooltip' => 'Products and materials ready for sale or use',
                'sort_order' => 4
            ],

            // Liabilities (Monthly Recurring Expenses)
            [
                'name' => 'Salary',
                'description' => 'Employee salaries and wages',
                'type' => 'liability',
                'icon' => 'fas fa-users',
                'color' => '#ffc107',
                'is_recurring' => true,
                'default_amount' => 5000.00,
                'tooltip' => 'Total monthly salary payments to all employees',
                'sort_order' => 1
            ],
            [
                'name' => 'Office Rent',
                'description' => 'Monthly office space rental',
                'type' => 'liability',
                'icon' => 'fas fa-building',
                'color' => '#ffc107',
                'is_recurring' => true,
                'default_amount' => 1500.00,
                'tooltip' => 'Monthly rent for office space and facilities',
                'sort_order' => 2
            ],
            [
                'name' => 'Electricity Bill',
                'description' => 'Monthly electricity costs',
                'type' => 'liability',
                'icon' => 'fas fa-bolt',
                'color' => '#ffc107',
                'is_recurring' => true,
                'default_amount' => 200.00,
                'tooltip' => 'Monthly electricity and power consumption charges',
                'sort_order' => 3
            ],
            [
                'name' => 'Internet Bill',
                'description' => 'Monthly internet and communication',
                'type' => 'liability',
                'icon' => 'fas fa-wifi',
                'color' => '#ffc107',
                'is_recurring' => true,
                'default_amount' => 100.00,
                'tooltip' => 'Internet, phone, and communication services',
                'sort_order' => 4
            ],
            [
                'name' => 'Tools & Software Bills',
                'description' => 'Monthly software subscriptions and tools',
                'type' => 'liability',
                'icon' => 'fas fa-tools',
                'color' => '#ffc107',
                'is_recurring' => true,
                'default_amount' => 300.00,
                'tooltip' => 'Software licenses, SaaS subscriptions, and business tools',
                'sort_order' => 5
            ],
            [
                'name' => 'Maid/Cleaning Service',
                'description' => 'Office cleaning and maintenance',
                'type' => 'liability',
                'icon' => 'fas fa-broom',
                'color' => '#ffc107',
                'is_recurring' => true,
                'default_amount' => 150.00,
                'tooltip' => 'Office cleaning, maintenance, and housekeeping services',
                'sort_order' => 6
            ],
            [
                'name' => 'Grocery/Office Supplies',
                'description' => 'Office supplies and consumables',
                'type' => 'liability',
                'icon' => 'fas fa-shopping-cart',
                'color' => '#ffc107',
                'is_recurring' => true,
                'default_amount' => 100.00,
                'tooltip' => 'Office supplies, snacks, beverages, and consumables',
                'sort_order' => 7
            ],
            [
                'name' => 'Insurance',
                'description' => 'Business insurance premiums',
                'type' => 'liability',
                'icon' => 'fas fa-shield-alt',
                'color' => '#ffc107',
                'is_recurring' => true,
                'default_amount' => 250.00,
                'tooltip' => 'Business insurance, health insurance, and liability coverage',
                'sort_order' => 8
            ],
            [
                'name' => 'Marketing Expenses',
                'description' => 'Advertising and marketing costs',
                'type' => 'liability',
                'icon' => 'fas fa-bullhorn',
                'color' => '#ffc107',
                'is_recurring' => true,
                'default_amount' => 500.00,
                'tooltip' => 'Advertising, marketing campaigns, and promotional activities',
                'sort_order' => 9
            ],
            [
                'name' => 'Taxes',
                'description' => 'Tax obligations and payments',
                'type' => 'liability',
                'icon' => 'fas fa-file-invoice-dollar',
                'color' => '#ffc107',
                'is_recurring' => true,
                'default_amount' => 800.00,
                'tooltip' => 'Income tax, sales tax, and other government obligations',
                'sort_order' => 10
            ],
            [
                'name' => 'Loan Payments',
                'description' => 'Business loan installments',
                'type' => 'liability',
                'icon' => 'fas fa-credit-card',
                'color' => '#ffc107',
                'is_recurring' => true,
                'default_amount' => 600.00,
                'tooltip' => 'Monthly loan payments and credit obligations',
                'sort_order' => 11
            ],

            // Equity
            [
                'name' => 'Owner Investment',
                'description' => 'Capital invested by owners',
                'type' => 'equity',
                'icon' => 'fas fa-user-tie',
                'color' => '#17a2b8',
                'is_recurring' => false,
                'default_amount' => 200000.00,
                'currency' => 'BDT',
                'tooltip' => 'Money invested by business owners and shareholders',
                'sort_order' => 1
            ],
            [
                'name' => 'Retained Earnings',
                'description' => 'Accumulated profits kept in business',
                'type' => 'equity',
                'icon' => 'fas fa-chart-line',
                'color' => '#17a2b8',
                'is_recurring' => false,
                'default_amount' => 75000.00,
                'currency' => 'BDT',
                'tooltip' => 'Profits earned and kept in the business for future use',
                'sort_order' => 2
            ],
            [
                'name' => 'Current Month Profit',
                'description' => 'This month\'s net profit',
                'type' => 'equity',
                'icon' => 'fas fa-trophy',
                'color' => '#17a2b8',
                'is_recurring' => false,
                'default_amount' => 25000.00,
                'currency' => 'BDT',
                'tooltip' => 'Net profit earned in the current month',
                'sort_order' => 3
            ]
        ];

        foreach ($categories as $category) {
            \DB::table('expense_categories')->insert(array_merge($category, [
                'created_at' => now(),
                'updated_at' => now()
            ]));
        }
    }
}
