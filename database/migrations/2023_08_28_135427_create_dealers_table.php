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
      Schema::create('dealers', function (Blueprint $table) {
        $table->id();
        $table->string('title', '40');
        $table->string('slug', '40')->unique();
        $table->string('city', '40')->nullable();
        $table->foreignId('user_id')
          ->index()
          ->constrained('users')
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
        Schema::dropIfExists('dealers');
    }
};
