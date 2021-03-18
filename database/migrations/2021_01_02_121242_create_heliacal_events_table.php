<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHeliacalEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('heliacal_events', function (Blueprint $table) {
            $table->id();
            $table->dateTime('expected_at');
            $table->string('visible_for', 12);
            $table->unsignedInteger('planet_id');
            $table->unsignedInteger('type_id');
            $table->unsignedBigInteger('city_id');

            $table->unique(['expected_at', 'planet_id', 'type_id', 'city_id']);
            $table->foreign('planet_id')->on('planets')->references('id');
            $table->foreign('type_id')->on('heliacal_event_types')->references('id');
            $table->foreign('city_id')->on('cities')->references('id');
            $table->index('expected_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('heliacal_events');
    }
}
