<?php

use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    $k = [array('method'=> "VIEW", 'name'=> "AdminIndex")];
	    $p = ["UserControl", "TicketControl", "BulletinControl", "AdminControl", "NodeControl", "RelayControl",
		    "GroupControl", "MerchandiseControl", "SupportControl", "MarketingControl", "SystemControl"];
	    $m = ["VIEW", "UPDATE", "INSERT", "DELETE"];
	    foreach($m as $t){
	    	foreach ($p as $z){
			    array_push($k, array('method'=> $t, 'name'=> $z));
		    }
	    }
	    DB::table('permissions')->insert($k);
	    DB::table('permissions')->where(['method', "DELETE"], ['name', "SystemControl"])->delete();
    }
}
