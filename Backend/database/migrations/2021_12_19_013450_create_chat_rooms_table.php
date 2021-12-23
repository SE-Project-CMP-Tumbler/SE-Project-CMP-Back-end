<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_rooms', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('from_blog_id');
            $table->unsignedInteger('to_blog_id');
            $table->unsignedInteger('last_cleared_id')->default(0);  # last cleared message id
            $table->unsignedInteger('last_sent_id')->default(0);     # last sent message id
            $table->timestamps();
            $table->unique(['from_blog_id', 'to_blog_id']);
            $table->foreign('from_blog_id')->references('id')->on('blogs')->onDelete('cascade');
            $table->foreign('to_blog_id')->references('id')->on('blogs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chat_rooms');
    }
}
