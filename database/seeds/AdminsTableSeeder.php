<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use \App\Model\Admin;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::create(['name' => 'sales'],['guard_name'=>'admin']);
        $role = Role::create(['name' => 'operation'],['guard_name'=>'admin']);
        $role = Role::create(['name' => 'complain'],['guard_name'=>'admin']);
        $faker = Faker\Factory::create();   
        $company = $faker->company;
        
        $data = new Admin();
        $data->name = $company;
        $data->email = $faker->safeEmail;
        $data->password = bcrypt('secret');
        $data->save();
        $data->assignRole('sales');

        $data = new Admin();
        $data->name = $company;
        $data->email = $faker->safeEmail;
        $data->password = bcrypt('secret');
        $data->save();
        $data->assignRole('operation');

        $data = new Admin();
        $data->name = $company;
        $data->email = $faker->safeEmail;
        $data->password = bcrypt('secret');
        $data->save();
        $data->assignRole('complain');
    }
}
