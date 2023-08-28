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
        Schema::create('sites', function (Blueprint $table) {
          $table->id();
          $table->string('favicon_image');
          $table->foreignId('dealer_id')
            ->index()
            ->constrained('dealers')
            ->cascadeOnUpdate()
            ->restrictOnDelete();
          $table->foreignId('user_id')
            ->index()
            ->constrained('users')
            ->cascadeOnUpdate()
            ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sites');
    }
};
