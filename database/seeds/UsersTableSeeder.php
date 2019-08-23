<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

      $faker = Faker\Factory::create(); 
      $limit = 10; 
    
      $role = new Role;
      $role->name = "sales";
      $role->guard_name = "web";
      $role->save();
      
      $role = new Role;
      $role->name = "operation";
      $role->guard_name = "web";
      $role->save();

      $role = new Role;
      $role->name = "complain";
      $role->guard_name = "web";
      $role->save();
      
      $faker = Faker\Factory::create();   
      $company = $faker->company;
      
      $data = new   \App\Model\User;
      $data->name = $company;
      $data->email = "sales@mail.com";
      $data->password = bcrypt('secret');
      $data->save();
      $data->assignRole('sales');

      $data = new \App\Model\User;
      $data->name = $company;
      $data->email = "operation@mail.com";
      $data->password = bcrypt('secret');
      $data->save();
      $data->assignRole('operation');

      $data = new \App\Model\User;
      $data->name = $company;
      $data->email = "complain@mail.com";
      $data->password = bcrypt('secret');
      $data->save();
      $data->assignRole('complain');
      
      for ($i = 0; $i < $limit; $i++) {
        $company = $faker->company;
        
        DB::table('users')->insert([
          'name' => $company,
          'email' => $faker->safeEmail,
          'password' => bcrypt('secret')
        ]);

        DB::table('detail_users')->insert([ 
          'user_id' => DB::getPdo()->lastInsertId(),
          'organization_name' => $company,
          'address' => $faker->streetAddress,
        ]);

      }
    }
}
