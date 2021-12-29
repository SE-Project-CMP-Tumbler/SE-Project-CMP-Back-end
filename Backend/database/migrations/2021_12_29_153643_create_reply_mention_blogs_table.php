<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReplyMentionBlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reply_mention_blogs', function (Blueprint $table) {
            $table->primary(['reply_id', 'blog_id']);

            $table->unsignedBigInteger('reply_id');
            $table->foreign('reply_id')
                ->references('id')
                ->on('replies')
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
        Schema::dropIfExists('reply_mention_blogs');
    }
}
