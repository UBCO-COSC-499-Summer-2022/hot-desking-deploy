<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resources_desks', function (Blueprint $table) {
            $table->id('resource_desk_id');
            $table->bigInteger('resource_id');
            $table->bigInteger('desk_id');
            $table->foreign('resource_id')->references('resource_id')->on('resources')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('desk_id')->references('id')->on('desks')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('resources_desks');
    }
};
