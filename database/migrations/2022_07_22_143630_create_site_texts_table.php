<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use \App\Constants\TableConstants;
use \App\Constants\Attributes\AttributeName;

return new class extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create(TableConstants::SITE_TEXTS, function (Blueprint $table) {
      $table->id();
      $table->morphs(AttributeName::MODEL);
      $table->text(AttributeName::BODY)->nullable();

      $table->foreignId(AttributeName::SITE_ID)
        ->index()
        ->constrained(TableConstants::SITES)
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
    Schema::dropIfExists(TableConstants::SITE_TEXTS);
  }
};
