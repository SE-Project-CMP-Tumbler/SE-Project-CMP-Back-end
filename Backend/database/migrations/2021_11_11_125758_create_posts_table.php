<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->longText('body');
            $table->string('type')->default('general');
            $table->boolean('pinned')->default(false);
            $table->string('status')->default('published');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->unsignedBigInteger('approving_blog_id')->nullable(); //The id of the blog that have approved this post if it was a submission status.
            $table->foreign('approving_blog_id')
                ->references('id')
                ->on('blogs')
                ->onDelete('cascade');

            $table->unsignedBigInteger('blog_id');
            $table->foreign('blog_id')
                ->references('id')
                ->on('blogs')
                ->onDelete('cascade');

            //a nullble parent means it has no parent, means this post is an original post
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreign('parent_id')
                ->references('id')
                ->on('posts')
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
        Schema::dropIfExists('posts');
    }
}
