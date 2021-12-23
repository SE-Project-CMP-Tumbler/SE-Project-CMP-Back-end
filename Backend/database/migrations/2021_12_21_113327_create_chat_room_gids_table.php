<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatRoomGIDsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_room_gids', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('chat_room_one_id');
            $table->unsignedInteger('chat_room_two_id');
            $table->timestamps();
            $table->unique(['chat_room_one_id', 'chat_room_two_id']);
            # TODO: Delete the entire row ?!
            $table->foreign('chat_room_one_id')->references('id')->on('chat_rooms')->onDelete('cascade');
            $table->foreign('chat_room_two_id')->references('id')->on('chat_rooms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chat_room_gids');
    }
}
