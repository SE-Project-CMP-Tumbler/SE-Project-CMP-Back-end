<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Http\Misc\Helpers\Config;

class CreateBlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('title');
            $table->string('password')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->text('description')->nullable();
            $table->text('avatar')->default(Config::DEFAULT_AVATAR);
            $table->text('header_image')->default(Config::DEFAULT_HEADER_IMAGE);
            $table->string('avatar_shape')->default('circle');
            $table->boolean('allow_messages')->default(false);
            $table->string('replies_settings')->default('Everyone can reply');
            $table->boolean('allow_submittions')->default(false);
            $table->text('submissions_page_title')->nullable();
            $table->text('submissions_guidelines')->nullable();
            $table->boolean('allow_ask')->default(false);
            $table->text('ask_page_title')->nullable();
            $table->boolean('allow_anonymous_questions')->default(false);
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id') ->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('blogs');
    }
}
