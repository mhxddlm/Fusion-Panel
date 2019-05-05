<?php

use Illuminate\Database\Seeder;

class InviteCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    DB::table('invite_codes')->insert([
		    'code' => 'ObjectNotFound',
		    'gen_by' => 0,
		    'used_count' => 0,
		    'limit' => 1
	    ]);
    }
}
