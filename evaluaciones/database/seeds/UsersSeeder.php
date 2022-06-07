<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Jorge',
            'email' => 'jmatricali@propositiva.com.ar',
            'password' => Hash::make('123456'),
        ]);
    }
}
