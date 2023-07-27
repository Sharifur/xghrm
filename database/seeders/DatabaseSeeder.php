<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Employee;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $allEmployee = Employee::where('status',1)->get();
        //run foreach
        //set zketeco user id into database automatically



        // \App\Models\User::factory(10)->create();
//        Admin::create([
//            'name' => 'Sharifur Robin',
//            'username' => 'dvrobin4',
//            'email' => 'dvrobin4@gmail.com',
//            'password' => \Hash::make(12345678),
//            'mobile' => '01847111881'
//        ]);
    }
}
