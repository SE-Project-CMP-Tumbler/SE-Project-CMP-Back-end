<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ask_sender_blog_id');
            $table->unsignedBigInteger('ask_reciever_blog_id');
            $table->longText('body');
            $table->boolean('anonymous_flag');
            $table->timestamps();

            $table->foreign('ask_sender_blog_id')
                ->references('id')
                ->on('blogs')
                ->onDelete('cascade');

            $table->foreign('ask_reciever_blog_id')
                ->references('id')
                ->on('blogs')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('questions');
    }
}
