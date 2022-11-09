<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdditionalSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('additional_sections', function (Blueprint $table) {
            $table->increments('id');
            $table->string('image_path')->nullable();
            $table->integer('pages_id');
            $table->integer('section_types_id');
            $table->timestamps();
        });
        Schema::create('additional_section_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('section_id')->unsigned();
            $table->string('locale')->index();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->unique(['section_id','locale']);
            $table->foreign('section_id')->references('id')->on('additional_sections')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('additional_sections');
    }
}