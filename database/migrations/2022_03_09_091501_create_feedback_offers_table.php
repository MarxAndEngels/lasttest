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
    Schema::create(TableConstants::FEEDBACK_OFFERS, function (Blueprint $table) {
      $table->id();
      $table->string(AttributeName::OFFER_TITLE)->nullable();
      $table->year(AttributeName::YEAR);
      $table->integer(AttributeName::EXTERNAL_ID)->nullable();
      $table->string(AttributeName::EXTERNAL_UNIQUE_ID)->nullable();

      $table->smallInteger(AttributeName::ENGINE_POWER)->default(0);
      $table->bigInteger(AttributeName::RUN)->default(0);

      $table->float(AttributeName::ENGINE_VOLUME, 2, 1)->nullable();
      $table->float(AttributeName::PRICE, 12, 2);
      $table->float(AttributeName::PRICE_OLD, 12, 2);

      $table->foreignId(AttributeName::MARK_ID)
        ->index()
        ->constrained(TableConstants::MARKS)
        ->cascadeOnUpdate()
        ->restrictOnDelete();
      $table->foreignId(AttributeName::FOLDER_ID)
        ->index()
        ->constrained(TableConstants::FOLDERS)
        ->cascadeOnUpdate()
        ->restrictOnDelete();
      $table->foreignId(AttributeName::GENERATION_ID)
        ->index()
        ->constrained(TableConstants::GENERATIONS)
        ->cascadeOnUpdate()
        ->restrictOnDelete();
      $table->foreignId(AttributeName::MODIFICATION_ID)
        ->index()
        ->constrained(TableConstants::MODIFICATIONS)
        ->cascadeOnUpdate()
        ->restrictOnDelete();
      $table->foreignId(AttributeName::GEARBOX_ID)
        ->index()
        ->constrained(TableConstants::GEARBOXES)
        ->cascadeOnUpdate()
        ->restrictOnDelete();
//      $table->foreignId(AttributeName::FEEDBACK_ID)
//        ->index()
//        ->constrained(TableConstants::FEEDBACKS)
//        ->restrictOnDelete();
      $table->foreignId(AttributeName::DEALER_ID)
        ->index()
        ->constrained(TableConstants::DEALERS)
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
    Schema::dropIfExists(TableConstants::FEEDBACK_OFFERS);
  }
};
