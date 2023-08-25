<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Constants\TableConstants;
use App\Constants\Attributes\AttributeName;
use \App\Constants\Enums\FeedbackEnum;
return new class extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create(TableConstants::FEEDBACKS, function (Blueprint $table) {
      $table->id();
      $table->bigInteger(AttributeName::EXTERNAL_ID)->nullable();
      $table->string(AttributeName::CLIENT_IP)->nullable();
      $table->string(AttributeName::CLIENT_SESSION)->nullable();
      $table->string(AttributeName::CLIENT_USER_AGENT, 1000)->nullable();
      $table->string(AttributeName::CLIENT_NAME, 100)->nullable();
      $table->string(AttributeName::CLIENT_PHONE, 50);
      $table->string(AttributeName::CLIENT_AGE, 50)->nullable();
      $table->string(AttributeName::CLIENT_REGION, 100)->nullable();
      $table->string(AttributeName::CLIENT_VEHICLE_MARK, 50)->nullable();
      $table->string(AttributeName::CLIENT_VEHICLE_MODEL, 50)->nullable();
      $table->string(AttributeName::CLIENT_VEHICLE_RUN, 50)->nullable();
      $table->string(AttributeName::CLIENT_VEHICLE_YEAR, 10)->nullable();
      $table->string(AttributeName::CLIENT_VEHICLE_BODY_TYPE, 50)->nullable();
      $table->string(AttributeName::CLIENT_VEHICLE_PRICE, 50)->nullable();
      $table->string(AttributeName::CLIENT_VEHICLE_OWNERS, 50)->nullable();
      $table->string(AttributeName::CLIENT_VEHICLE_GEARBOX, 50)->nullable();
      $table->string(AttributeName::CLIENT_VEHICLE_ENGINE, 50)->nullable();

      $table->string(AttributeName::CREDIT_INITIAL_FEE, 50)->nullable();
      $table->string(AttributeName::CREDIT_PERIOD, 50)->nullable();

      $table->string(AttributeName::UTM_SOURCE, 50)->nullable();
      $table->string(AttributeName::UTM_MEDIUM, 50)->nullable();
      $table->string(AttributeName::UTM_CAMPAIGN, 50)->nullable();
      $table->string(AttributeName::UTM_CONTENT, 50)->nullable();
      $table->string(AttributeName::UTM_TERM, 50)->nullable();

      $table->string(AttributeName::OFFER_TITLE)->nullable();
      $table->string(AttributeName::OFFER_PRICE, 50)->nullable();

      $table->text(AttributeName::COMMENT)->nullable();

      $table->enum(AttributeName::TYPE_ENUM, FeedbackEnum::TYPE_ENUM);
      $table->enum(AttributeName::STATUS_ENUM, FeedbackEnum::STATUS_ENUM);

      $table->foreignId(AttributeName::SITE_ID)
        ->index()
        ->constrained(TableConstants::SITES)
        ->cascadeOnUpdate()
        ->restrictOnDelete();
      $table->foreignId(AttributeName::CLIENT_REGION_ID)
        ->nullable()
        ->constrained(TableConstants::REGIONS)
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
    Schema::dropIfExists(TableConstants::FEEDBACKS);
  }
};
