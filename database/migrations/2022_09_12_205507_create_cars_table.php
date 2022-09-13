<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('provider_name');
            $table->integer('company_id')->default(0);
            $table->string('company');
            $table->string('route')->nullable();
            // $table->date('date');
            // $table->time('hour');
            $table->string('vin');
            $table->string('economic_number', 30)->nullable();
            $table->string('license_plate', 30)->nullable();
            $table->string('imei', 50);
            // $table->float('latitud');
            // $table->float('longitud');
            // $table->float('altitud')->nullable();
            // $table->float('speed');
            // $table->float('direction')->nullable();
            // $table->boolean('panic_button')->default(false);
            // $table->string('camera_url');
            $table->enum('unit_type', ['TAXI', 'VAGONETA', 'CAMIÓN']);
            $table->string('brand');
            $table->string('sub_brand');
            $table->string('model_date');
            $table->string('zone');
            $table->string('delegation');
            $table->string('municipality');
            $table->string('concession_number');
            // $table->boolean('active')->default(false);
            // $table->string('url_equipment_letter');
            // $table->date('installation_date');
            // $table->date('expiration_date');
            $table->bigInteger('id_navixy')->nullable();
            $table->bigInteger('id_cms')->nullable();
            $table->boolean('video')->default(false);
            $table->string('phone')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('cars');
    }
}
