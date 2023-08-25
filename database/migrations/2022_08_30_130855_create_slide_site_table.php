<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Constants\TableConstants;
use App\Constants\Attributes\AttributeName;
return new class extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create(TableConstants::SLIDE_SITE, function (Blueprint $table) {
      $table->id();
      $table->foreignId(AttributeName::SLIDE_ID)
        ->index()
        ->constrained(TableConstants::SLIDES)
        ->cascadeOnUpdate()
        ->cascadeOnDelete();
      $table->foreignId(AttributeName::SITE_ID)
        ->index()
        ->constrained(TableConstants::SITES)
        ->cascadeOnUpdate()
        ->cascadeOnDelete();

      $table->unique([
        AttributeName::SLIDE_ID,
        AttributeName::SITE_ID
      ]);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists(TableConstants::SLIDE_SITE);
  }
};
