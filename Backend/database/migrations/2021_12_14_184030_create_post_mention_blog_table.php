<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostMentionBlogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_mention_blog', function (Blueprint $table) {
            $table->primary(['post_id', 'blog_id']);

            $table->unsignedBigInteger('post_id');
            $table->foreign('post_id')
                ->references('id')
                ->on('posts')
                ->onDelete('cascade');

            $table->unsignedBigInteger('blog_id');
            $table->foreign('blog_id')
                ->references('id')
                ->on('blogs')
                ->onDelete('cascade');

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
        Schema::dropIfExists('post_mention_blog');
    }
}
