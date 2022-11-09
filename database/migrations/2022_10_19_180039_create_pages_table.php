<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('main_image_path')->nullable();
            $table->string('second_image_path')->nullable();
            $table->timestamps();
        });
        Schema::create('page_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pages_id')->unsigned();
            $table->string('locale')->index();
            $table->string('page_name');
            $table->string('slug');
            $table->string('main_title')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('slogan')->nullable();
            $table->text('main_description')->nullable();
            $table->text('second_description')->nullable();
            $table->unique(['pages_id','locale']);
            $table->foreign('pages_id')->references('id')->on('pages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('page_translations');
        Schema::dropIfExists('pages');
    }
}