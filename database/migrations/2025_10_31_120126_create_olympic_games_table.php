<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOlympicGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('olympic_games', function (Blueprint $table) {
            $table->id();
            $table->string('city'); // Город
            $table->integer('year'); // Год (число)
            $table->string('title'); // Заголовок
            $table->string('image_filename'); // Имя файла картинки (вместо image)
            $table->text('short_description'); // Краткое описание
            $table->text('detailed_description'); // Детальное описание  
            $table->text('fun_fact'); // Интересный факт
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
        Schema::dropIfExists('olympic_games');
    }
}
