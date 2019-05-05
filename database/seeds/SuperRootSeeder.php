<?php

use Illuminate\Database\Seeder;

class SuperRootSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    DB::table('users')->insert([
		    'id' => 0,
		    'name' => "Root",
		    'email' => "",
		    'password' => "",
		    'status' => true,
		    'invited_by' => 0
	    ]);

    }
}
