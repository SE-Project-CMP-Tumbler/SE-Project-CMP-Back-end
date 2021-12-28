<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateThemesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('themes', function (Blueprint $table) {
            $table->id();
            $table->text('color_title')->default('#000000');
            $table->text('font_title')->default('Gibson');
            $table->text('font_weight_title')->default('bold');
            $table->text('background_color')->default('#FFFFFF');
            $table->text('accent_color')->default('#e17e66');
            $table->text('body_font')->default('Helvetica Neue');
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
        Schema::dropIfExists('themes');
    }
}
