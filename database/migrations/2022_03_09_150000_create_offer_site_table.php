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
    Schema::create(TableConstants::OFFER_SITE, function (Blueprint $table) {
      $table->id();
      $table->float(AttributeName::PRICE, 12, 2)->index();
      $table->float(AttributeName::PRICE_OLD, 12, 2);
      $table->text(AttributeName::DESCRIPTION)->nullable();
      $table->boolean(AttributeName::IS_ACTIVE)->default(0)->index();
      $table->foreignId(AttributeName::OFFER_ID)
        ->index()
        ->constrained(TableConstants::OFFERS)
        ->cascadeOnUpdate()
        ->cascadeOnDelete();
      $table->foreignId(AttributeName::SITE_ID)
        ->index()
        ->constrained(TableConstants::SITES)
        ->cascadeOnUpdate()
        ->cascadeOnDelete();
      $table->unique([
        AttributeName::OFFER_ID,
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
    Schema::dropIfExists(TableConstants::OFFER_SITE);
  }
};
