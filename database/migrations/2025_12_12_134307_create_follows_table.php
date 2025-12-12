<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFollowsTable extends Migration
{
    public function up()
    {
        Schema::create('follows', function (Blueprint $table) {
            $table->id('follow_id');
            $table->unsignedBigInteger('subscriber_user_id');
            $table->unsignedBigInteger('author_user_id');
            $table->timestamp('created_at')->useCurrent();

            // Внешние ключи
            $table->foreign('subscriber_user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->foreign('author_user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->unique(['subscriber_user_id', 'author_user_id']);

            $table->index('subscriber_user_id');
            $table->index('author_user_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('follows');
    }
}