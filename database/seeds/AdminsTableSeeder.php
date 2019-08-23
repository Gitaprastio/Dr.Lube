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
        $role = new Role;
        $role->name = "sales";
        $role->guard_name = "admin";
        $role->save();
        
        $role = new Role;
        $role->name = "operation";
        $role->guard_name = "admin";
        $role->save();

        $role = new Role;
        $role->name = "complain";
        $role->guard_name = "admin";
        $role->save();
        
        $faker = Faker\Factory::create();   
        $company = $faker->company;
        
        $data = new Admin();
        $data->name = $company;
        $data->email = "sales@mail.com";
        $data->password = bcrypt('secret');
        $data->save();
        $data->assignRole('sales');

        $data = new Admin();
        $data->name = $company;
        $data->email = "operation@mail.com";
        $data->password = bcrypt('secret');
        $data->save();
        $data->assignRole('operation');

        $data = new Admin();
        $data->name = $company;
        $data->email = "complain@mail.com";
        $data->password = bcrypt('secret');
        $data->save();
        $data->assignRole('complain');
    }
}
