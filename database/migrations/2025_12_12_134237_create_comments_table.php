<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id('comment_id');
            $table->unsignedBigInteger('game_id');
            $table->unsignedBigInteger('user_id');
            $table->string('text', 500);
            $table->timestamps();

            // fk
            $table->foreign('game_id')
                  ->references('id')
                  ->on('olympic_games')
                  ->onDelete('cascade');

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->index('game_id');
            $table->index('user_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('comments');
    }
}