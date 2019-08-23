<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class AgreementTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

      $faker = Faker\Factory::create(); 
      $limit = 20; 
    
      for ($i = 0; $i < $limit; $i++) {
        $company = $faker->company;

        $date = Carbon::create(2019, 8, 22, 0, 0, 0);
        
        DB::table('product_agreements')->insert([
          'user_id' => App\Model\User::all()->random()->id,
          'contract_date_start' => $date->format('Y-m-d'),
          'contract_date_end' => $date->addWeeks(rand(1, 52))->format('Y-m-d')
        ]);

        $id = DB::getPdo()->lastInsertId();
        for ($j = 0; $j < 5; $j++) {
          
          DB::table('agreements_list_items')->insert([
            'cost' => $faker->randomFloat($nbMaxDecimals = 3, $min = 1, $max = 1000),
            'quantity' => $faker->numberBetween($min = 10000, $max = 90000),
            'product_agreement_id' => $id,
            'product_id' => App\Model\Product::all()->random()->id
          ]);
  
        }

      }
    }
}
