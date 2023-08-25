<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Constants\TableConstants;
use App\Constants\Attributes\AttributeName;
use \App\Constants\Enums\OfferEnum;
return new class extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create(TableConstants::OFFERS, function (Blueprint $table) {
      $table->id();
      $table->integer(AttributeName::EXTERNAL_ID)->index();
      $table->string(AttributeName::EXTERNAL_UNIQUE_ID)->unique();

      $table->string(AttributeName::NAME);
      $table->enum(AttributeName::CATEGORY_ENUM, OfferEnum::CATEGORY_ENUM)->index();
      $table->enum(AttributeName::SECTION_ENUM, OfferEnum::SECTION_ENUM)->index();
      $table->enum(AttributeName::TYPE_ENUM, OfferEnum::TYPE_ENUM)->index();
      $table->string(AttributeName::STATE_ENUM)->index()->nullable();

      $table->string(AttributeName::VIN)->nullable();
      $table->string(AttributeName::VIDEO)->nullable();

      $table->year(AttributeName::YEAR)->index();

      $table->smallInteger(AttributeName::ENGINE_POWER)->default(0)->index();
      $table->bigInteger(AttributeName::RUN)->default(0)->index();



      $table->float(AttributeName::ENGINE_VOLUME, 2, 1)->nullable();
      $table->float(AttributeName::RATING_TECHNICAL, 2, 1)->nullable();
      $table->float(AttributeName::RATING_BODY, 2, 1)->nullable();
      $table->float(AttributeName::RATING_INTERIOR, 2, 1)->nullable();
      $table->float(AttributeName::RATING, 2, 1)->default(4)->index();

      $table->smallInteger(AttributeName::COMMUNICATIONS_COUNT)->unsigned()->default(0);
      $table->smallInteger(AttributeName::CONTACT_FORM_APPLICATIONS_COUNT)->unsigned()->default(0);
      $table->smallInteger(AttributeName::PHONE_CALLS_COUNT)->unsigned()->default(0);


      $table->json(AttributeName::IMAGES);
      $table->json(AttributeName::EQUIPMENT);
      $table->json(AttributeName::EQUIPMENT_GROUPS);
      $table->json(AttributeName::SPECIFICATIONS);

      $table->foreignId(AttributeName::OFFER_COMMERCIAL_TYPE_ID)
        ->nullable()
        ->constrained(TableConstants::OFFER_COMMERCIAL_TYPES)
        ->cascadeOnUpdate()
        ->restrictOnDelete();
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
        ->nullable()
        ->constrained(TableConstants::GENERATIONS)
        ->cascadeOnUpdate()
        ->restrictOnDelete();
      $table->foreignId(AttributeName::MODIFICATION_ID)
        ->index()
        ->nullable()
        ->constrained(TableConstants::MODIFICATIONS)
        ->cascadeOnUpdate()
        ->restrictOnDelete();
      $table->foreignId(AttributeName::COMPLECTATION_ID)
        ->index()
        ->nullable()
        ->constrained(TableConstants::COMPLECTATIONS)
        ->cascadeOnUpdate()
        ->restrictOnDelete();
      $table->foreignId(AttributeName::GEARBOX_ID)
        ->index()
        ->constrained(TableConstants::GEARBOXES)
        ->cascadeOnUpdate()
        ->restrictOnDelete();
      $table->foreignId(AttributeName::DRIVE_TYPE_ID)
        ->index()
        ->constrained(TableConstants::DRIVE_TYPES)
        ->cascadeOnUpdate()
        ->restrictOnDelete();
      $table->foreignId(AttributeName::ENGINE_TYPE_ID)
        ->index()
        ->constrained(TableConstants::ENGINE_TYPES)
        ->cascadeOnUpdate()
        ->restrictOnDelete();
      $table->foreignId(AttributeName::BODY_TYPE_ID)
        ->index()
        ->constrained(TableConstants::BODY_TYPES)
        ->cascadeOnUpdate()
        ->restrictOnDelete();
      $table->foreignId(AttributeName::COLOR_ID)
        ->nullable()
        ->constrained(TableConstants::COLORS)
        ->cascadeOnUpdate()
        ->restrictOnDelete();
      $table->foreignId(AttributeName::WHEEL_ID)
        ->index()
        ->constrained(TableConstants::WHEELS)
        ->cascadeOnUpdate()
        ->restrictOnDelete();
      $table->foreignId(AttributeName::OWNER_ID)
        ->index()
        ->nullable()
        ->constrained(TableConstants::OWNERS)
        ->cascadeOnUpdate()
        ->restrictOnDelete();

      $table->foreignId(AttributeName::DEALER_ID)
        ->index()
        ->constrained(TableConstants::DEALERS)
        ->cascadeOnUpdate()
        ->cascadeOnDelete();

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
    Schema::dropIfExists(TableConstants::OFFERS);
  }
};
