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
    Schema::create(TableConstants::STATIONS, function (Blueprint $table) {
      $table->id();
      $table->string(AttributeName::TITLE, 180);
      $table->string(AttributeName::BODY)->nullable();
      $table->string(AttributeName::PRICE, 30)->nullable();


      $table->foreignId(AttributeName::STATION_CATEGORY_ID)
        ->index()
        ->constrained(TableConstants::STATION_CATEGORIES)
        ->cascadeOnUpdate()
        ->cascadeOnDelete();
      $table->boolean(AttributeName::IS_ACTIVE)->default(0);

      $table->unsignedMediumInteger(AttributeName::ORDER_COLUMN)->default(9999)->nullable()->index();

      $table->index([AttributeName::ORDER_COLUMN, AttributeName::STATION_CATEGORY_ID, AttributeName::IS_ACTIVE]);

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
    Schema::dropIfExists(TableConstants::STATIONS);
  }
};
