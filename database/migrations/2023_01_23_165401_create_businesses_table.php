<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->ulid('id');
            $table->string('alias');
            $table->string('name');
            $table->string('image_url');
            $table->boolean('is_closed');
            $table->integer('review_count');
            $table->smallInteger('rating');
            $table->double('latitude');
            $table->double('longitude');
            $table->tinyText('price');
            $table->string('address1');
            $table->string('address2');
            $table->string('city');
            $table->tinyText('zip_code');
            $table->string('country');
            $table->tinyText('state');
            $table->string('phone');
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
        Schema::dropIfExists('businesses');
    }
};
