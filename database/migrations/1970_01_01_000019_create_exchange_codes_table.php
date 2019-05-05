<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExchangeCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exchange_codes', function (Blueprint $table) {
            $table->increments('id');
            $table->text('code');
            $table->double('money', 8, 2);
            $table->integer('used_count')->default(0)->unsigned();
            $table->integer('limit')->unsigned();
            $table->dateTime('expire_at')->nullable();
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
        Schema::dropIfExists('exchange_codes');
    }
}
