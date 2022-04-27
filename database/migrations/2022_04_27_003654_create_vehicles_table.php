<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string("icon_color")->nullable();
            $table->bigInteger("tracker_id")->nullable()->unsigned();
            $table->string("tracker_label")->nullable();
            $table->string("label")->nullable();
            $table->string("max_speed")->nullable();
            $table->string("model")->nullable();
            $table->string("type")->nullable();
            $table->string("subtype")->nullable();
            $table->string("color")->nullable();
            $table->string("additional_info")->nullable();
            $table->string("reg_number")->nullable();
            $table->string("vin")->nullable();
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
        Schema::dropIfExists('vehicles');
    }
}
