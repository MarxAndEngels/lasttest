<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Constants\TableConstants;
use App\Constants\Attributes\AttributeName;

return new class extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create(TableConstants::REGION_SITE, function (Blueprint $table) {
      $table->id();
      $table->integer(AttributeName::ORDER_COLUMN)->default(9999)->index();
      $table->foreignId(AttributeName::REGION_ID)
        ->index()
        ->constrained(TableConstants::REGIONS)
        ->cascadeOnUpdate()
        ->cascadeOnDelete();
      $table->foreignId(AttributeName::SITE_ID)
        ->index()
        ->constrained(TableConstants::SITES)
        ->cascadeOnUpdate()
        ->cascadeOnDelete();

      $table->unique([
        AttributeName::REGION_ID,
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
    Schema::dropIfExists(TableConstants::REGION_SITE);
  }
};
