<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('serial_number');
            $table->string('article_file_path');
            $table->string('audio_file_path');
            $table->string('cover_file_path');
            $table->float('price');
            $table->integer('category_id');
            $table->integer('pages_count');
            $table->string('status')->nullable();
            $table->boolean('isApproved')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('article_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('article_id')->unsigned();
            $table->string('locale')->index();
            $table->string('title');
            $table->text('summary');
            $table->text('description');
            $table->string('language')->nullable();
            $table->unique(['article_id','locale']);
            $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('article_translations');
        Schema::dropIfExists('articles');
    }
}
