<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blocks', function (Blueprint $table) {
            $table->unsignedBigInteger('blocker_id'); //The id of the one doing the block action
            $table->foreign('blocker_id')
                ->references('id')
                ->on('blogs')
                ->onDelete('cascade');

            $table->unsignedBigInteger('blocked_id'); //The id of the one on which the block is done
            $table->foreign('blocked_id')
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
        Schema::dropIfExists('blocks');
    }
}
