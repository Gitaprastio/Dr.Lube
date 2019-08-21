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
        DB::table('users')->insert([
            'name' => 'Perusahaan A',
            'email' => 'a@mail.com',
            'password' => bcrypt('secret')
        ]);
    }
}
