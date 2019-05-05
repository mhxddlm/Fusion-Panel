<?php

use Illuminate\Database\Seeder;

class SystemConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    DB::table('system_configs')->insert([
	    	['name' => "First rebate percent", 'code' => "first_rebate_percent", 'value' => "0", 'description' => "How much will user's inviter get when they charge for the first time."],
		    ['name' => "Default invite code amount", 'code' => "default_invite_code", 'value' => "3", 'description' => "Invite code amount a new user will get."],
		    ['name' => "Force register with invite code", 'code' => "force_register_w_code", 'value' => "1", 'description' => "Users must register with an valid invite code."],
		    ['name' => "Normal rebate percent", 'code' => "normal_rebate_percent", 'value' => "0", 'description' => "How much will user's inviter get when they charge for the rest of the times."],
		    ['name' => "Rebate status", 'code' => "rebate_status", 'value' => "1", 'description' => "0 for enable, 1 for disable."]
	    ]);
    }
}
