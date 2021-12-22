<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFollowBlogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('follow_blog', function (Blueprint $table) {
            $table->primary(['follower_id','followed_id']);
            $table->unsignedInteger('follower_id');
            $table->unsignedInteger('followed_id');
            $table->foreign('follower_id')->references('id')->on('blogs') ->onDelete('cascade');
            $table->foreign('followed_id')->references('id')->on('blogs')  ->onDelete('cascade');
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
        Schema::dropIfExists('follow_blog');
    }
}
