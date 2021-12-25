<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('submissions', function (Blueprint $table) {
            $table->unsignedBigInteger('submitter_id');
            $table->foreign('submitter_id')
                ->references('id')
                ->on('blogs')
                ->onDelete('cascade');

            $table->unsignedBigInteger('reciever_id');
            $table->foreign('reciever_id')
                ->references('id')
                ->on('blogs')
                ->onDelete('cascade');

            $table->unsignedBigInteger('post_id');
            $table->foreign('post_id')
                ->references('id')
                ->on('posts')
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
        Schema::dropIfExists('submissions');
    }
}
