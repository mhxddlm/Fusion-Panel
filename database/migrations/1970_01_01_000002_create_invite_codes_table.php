<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInviteCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invite_codes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code');
	        $table->double('money_include',8,2)->default(0.00);
            $table->integer('gen_by');
	        $table->foreign('gen_by')->references('id')->on('users')->onDelete('cascade');
            $table->integer('used_count')->default(0)->unsigned();
	        $table->integer('limit')->default(0)->unsigned();
	        $table->boolean('enable')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invite_codes');
    }
}
