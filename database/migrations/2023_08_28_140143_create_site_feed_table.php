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
        Schema::create('site_feed', function (Blueprint $table) {
          $table->id();
          $table->foreignId('site_id')
            ->index()
            ->constrained('sites')
            ->cascadeOnUpdate()
            ->restrictOnDelete();
          $table->foreignId('feed_id')
            ->index()
            ->constrained('feeds')
            ->cascadeOnUpdate()
            ->restrictOnDelete();
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
        Schema::dropIfExists('site_feeds');
    }
};
