<?php

use Illuminate\Database\Seeder;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admin')->insert([
            'name' => env('INITIAL_ADMIN_NAME'),
            'email' => env('INITIAL_ADMIN_EMAIL'),
            'mobile' => env('INITIAL_ADMIN_MOBILE'),
            'type' => 1,
            'permission' => '',
            'password' => Hash::make(env('INITIAL_ADMIN_PASSWORDHASH')),
          ]);
    }
}
