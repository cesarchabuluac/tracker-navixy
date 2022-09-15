<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsOnVehicleTrackersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vehicle_trackers', function (Blueprint $table) {
            $table->integer('speed')->default(0)->after('lng');
            $table->integer('alt')->default(0)->after('speed');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehicle_trackers', function (Blueprint $table) {
            $table->dropColumn('speed');
            $table->dropColumn('alt');
        });
    }
}
