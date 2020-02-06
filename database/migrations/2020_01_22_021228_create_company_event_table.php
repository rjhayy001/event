<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyEventTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_event', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('paidprice')->nullable();
            $table->string('emplacement')->nullable();
            $table->integer('is_restaurant')->nullable();
            $table->string('x')->nullable();
            $table->string('y')->nullable();
            $table->string('description')->nullable();
            $table->integer('highlight')->nullable();
            $table->integer('company_id')->unsigned();
            $table->integer('event_id')->unsigned();
            $table->timestamps();
            $table->foreign('event_id')
                ->references('id')->on('events')->onDelete('cascade');
            $table->foreign('company_id')
                ->references('id')->on('companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_event');
    }
}
