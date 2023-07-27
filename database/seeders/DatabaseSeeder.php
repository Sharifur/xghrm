<?php

namespace Database\Seeders;

use App\Helpers\ZktecoHelper;
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
        $zkHelper = ZktecoHelper::init();
        $getUsers= $zkHelper->users();

        foreach ($allEmployee as $employee){
            $zkUser = $getUsers->where("name",$employee->att_id)->first();
            Employee::where(['id' =>$employee->id])->update([
                'zktecho_user_id' => $zkUser['userid'] ?? 0
            ]);
        }


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
