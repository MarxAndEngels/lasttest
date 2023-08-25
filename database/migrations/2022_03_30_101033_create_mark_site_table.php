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
    Schema::create(TableConstants::MARK_SITE, function (Blueprint $table) {
      $table->id();
      $table->unsignedMediumInteger(AttributeName::ORDER_COLUMN)->default(9999)->index();
      $table->foreignId(AttributeName::MARK_ID)
        ->index()
        ->constrained(TableConstants::MARKS)
        ->cascadeOnUpdate()
        ->cascadeOnDelete();
      $table->foreignId(AttributeName::SITE_ID)
        ->index()
        ->constrained(TableConstants::SITES)
        ->cascadeOnUpdate()
        ->cascadeOnDelete();

      $table->unique([
        AttributeName::MARK_ID,
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
    Schema::dropIfExists(TableConstants::MARK_SITE);
  }
};
