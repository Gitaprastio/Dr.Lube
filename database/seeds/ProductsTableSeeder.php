<?php

use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
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
      $name = array('FASTRON', 'PRIMA', 'MESRAN', 'TERMO', 'GREASE', 'MAXCOOL');
    
      for ($i = 0; $i < $limit; $i++) {
        
        DB::table('products')->insert([
          'product_name' => $faker->randomElement($name) . '-' . $faker->numberBetween($min = 100, $max = 999),
          'product_code' => $faker->swiftBicNumber,
          'product_desc' => $faker->text($maxNbChars = 255)
        ]);

        DB::table('detail_product')->insert([ 
          'product_id' => DB::getPdo()->lastInsertId(),
          'stock' => $faker->numberBetween($min = 10000, $max = 900000)
        ]);

      }
    }
}
