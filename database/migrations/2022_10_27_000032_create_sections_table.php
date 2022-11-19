<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->increments('id');
            $table->string('image_path')->nullable();
            $table->integer('section_types_id');
            $table->timestamps();
        });

        Schema::create('section_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('section_id')->unsigned();
            $table->string('locale')->index();
            $table->string('title')->nullable();
            $table->morphs('sectionable');
            $table->text('description')->nullable();
            $table->unique(['section_id','locale']);
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('section_translations');
        Schema::dropIfExists('sections');
    }
}
