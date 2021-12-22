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
            $table->string('from_blog_username');
            $table->string('to_blog_username');
            $table->unsignedInteger('last_cleared_id')->default(0);  # last cleared message id
            $table->unsignedInteger('last_sent_id')->default(0);     # last sent message id
            $table->timestamps();
            $table->unique(['from_blog_username', 'to_blog_username']);
            $table->foreign('from_blog_username')->references('username')->on('blogs')->onDelete('cascade');
            $table->foreign('to_blog_username')->references('username')->on('blogs')->onDelete('cascade');
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
