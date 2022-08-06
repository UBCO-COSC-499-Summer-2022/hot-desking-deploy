<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoleRoomPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_room', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->index();
            $table->foreign('role_id')->references('role_id')->on('roles')->onDelete('cascade');
            $table->unsignedBigInteger('room_id')->index();
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
            $table->primary(['role_id', 'room_id']);
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
        Schema::dropIfExists('role_room');
    }
}
