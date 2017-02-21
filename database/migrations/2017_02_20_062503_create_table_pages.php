<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function ($table) {
            $table->increments('id');
            $table->string('title');
            $table->string('slug');
            $table->longText('content');
            $table->string('thumbnail')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_keyword')->nullable();
            $table->text('meta_description')->nullable();
            $table->integer('author_id');
            $table->tinyInteger('status')->default(0);
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
        Schema::drop('pages');
    }
}
