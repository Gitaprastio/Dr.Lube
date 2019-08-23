<?php

use Illuminate\Database\Seeder;

class AgreementItemTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

      $faker = Faker\Factory::create(); 
      $limit = 30; 
    
      for ($i = 0; $i < $limit; $i++) {
        $company = $faker->company;
        
        DB::table('agreements_list_items')->insert([
          'cost' => $faker->randomFloat($nbMaxDecimals = 3, $min = 1, $max = 1000),
          'quantity' => $faker->numberBetween($min = 10000, $max = 90000),
          'product_agreement_id' => App\Model\ProductAgreement::all()->random()->id,
          'product_id' => App\Model\Product::all()->random()->id
        ]);

      }
    }
}
