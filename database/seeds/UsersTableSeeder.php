<?php

use Illuminate\Database\Seeder;

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
          'address' => $faker->lastName,
        ]);

      }
    }
}
