<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHomePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('home_pages', function (Blueprint $table) {
            $table->id();
            $table->string('page_name');
            $table->string('first_section_title');
            $table->string('first_side_image');
            $table->string('third_section_title');
            $table->text('third_left_description');
            $table->text('third_right_description');
            $table->string('third_side_image');
            $table->timestamps();
        });
        Schema::create('home_pages', function (Blueprint $table) {
            $table->id();
            $table->string('page_name');
            $table->string('first_section_title');
            $table->string('first_side_image');
            $table->string('third_section_title');
            $table->text('third_left_description');
            $table->text('third_right_description');
            $table->string('third_side_image');
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
        Schema::dropIfExists('home_pages');
    }
}