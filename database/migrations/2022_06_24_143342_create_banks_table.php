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
    Schema::create(TableConstants::BANKS, function (Blueprint $table) {
      $table->id();
      $table->string(AttributeName::NAME);
      $table->string(AttributeName::TITLE)->nullable();
      $table->string(AttributeName::SLUG)->unique();
      $table->string(AttributeName::LICENSE_TITLE, 50)->nullable();
      $table->unsignedSmallInteger(AttributeName::REQUEST, false)->index();
      $table->unsignedSmallInteger(AttributeName::APPROVAL, false)->index();
      $table->unsignedFloat(AttributeName::RATE, 2, 2)->index();
      $table->unsignedFloat(AttributeName::RATING, 2, 2)->index();
      $table->boolean(AttributeName::IS_ACTIVE)->default(0)->index();
      $table->index([AttributeName::RATING, AttributeName::IS_ACTIVE]);
      $table->index([AttributeName::SLUG, AttributeName::IS_ACTIVE]);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists(TableConstants::BANKS);
  }
};
