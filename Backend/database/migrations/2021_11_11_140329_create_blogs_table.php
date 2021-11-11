<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            // check this??
            $table->string('blog_username')->unique();
            $table->string('title');
            $table->string('password')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->text('description')->nullable();
            $table->text('avatar')->nullable();
            $table->text('header_image')->nullable();
            //??
            //$table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            // settings
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
