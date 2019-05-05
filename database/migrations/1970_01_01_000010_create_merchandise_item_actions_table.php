<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMerchandiseItemActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchandise_item_actions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('m_item_id')->unsigned();;
            $table->foreign('m_item_id')->references('id')->on('merchandise_items')->onDelete('cascade');
            $table->integer('group')->unsigned();
	        $table->foreign('group')->references('id')->on('group_infos')->onDelete('cascade');
	        $table->text('action');
	        $table->integer('value');
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
        Schema::dropIfExists('merchandise_item_actions');
    }
}
