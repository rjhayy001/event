<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->string('image')->nullable();
            $table->string('name')->unique();
            $table->date('fromdate')->nullable();
            $table->date('todate')->nullable();
            $table->text('description')->nullable();
            $table->string('place')->nullable();
            $table->string('map')->nullable();
            $table->string('fullmap')->nullable();
            $table->string('presentation_image')->nullable();
            $table->string('programme_image')->nullable();
            $table->string('tarifs_image')->nullable();
            $table->string('plan_image')->nullable();
            $table->string('restaurant_image')->nullable();
            $table->string('highlights_image')->nullable();
            $table->string('logo')->nullable();
            $table->boolean('notify')->default(false);
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
        Schema::dropIfExists('events');
    }
}
