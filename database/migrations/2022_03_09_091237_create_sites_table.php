<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
Use App\Constants\TableConstants;
Use App\Constants\Attributes\AttributeName;
return new class extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create(TableConstants::SITES, function (Blueprint $table) {
      $table->id();
      $table->integer(AttributeName::EXTERNAL_ID)->unique();
      $table->string(AttributeName::CATEGORY_URL)->default('cars');
      $table->json(AttributeName::CATEGORY_ASSOCIATION)->nullable();
      $table->json(AttributeName::ROUTE_PAGES)->nullable();
      $table->boolean(AttributeName::BANK_PAGES)->default(0);
      $table->boolean(AttributeName::DEALER_PAGES)->default(0);
      $table->boolean(AttributeName::GENERATION_URL)->default(0);
      $table->boolean(AttributeName::POST_LINK_CRM)->default(0);
//      $table->boolean(AttributeName::POST_LINK_CRM)->default(0);
      $table->boolean(AttributeName::POST_FEEDBACK_PLEX_CRM)->index()->default(1);
      $table->boolean(AttributeName::POST_FEEDBACK_EMAIL)->index()->default(0);
      $table->boolean(AttributeName::GET_COMMUNICATIONS)->index()->default(0);
      $table->string(AttributeName::SLUG)->nullable()->unique();
      $table->string(AttributeName::TITLE);
      $table->string(AttributeName::URL)->unique();
      $table->json(AttributeName::FILTER)->nullable();
      $table->string(AttributeName::EMAIL_SERVICE, 50)->nullable();
      $table->string(AttributeName::FEEDBACK_EMAIL, 50)->nullable();
      $table->boolean(AttributeName::IS_DISABLED)->index()->default(0);

      $table->dateTime(AttributeName::API_DATE_FROM)->nullable();
      $table->dateTime(AttributeName::API_DATE_LAST)->nullable();
      $table->timestamps();

      $table->foreignId(AttributeName::DEALER_ID)
        ->index()
        ->nullable()
        ->constrained(TableConstants::DEALERS)
        ->cascadeOnUpdate()
        ->restrictOnDelete();
      $table->foreignId(AttributeName::PARENT_SITE_ID)
        ->index()
        ->nullable()
        ->constrained(TableConstants::SITES)
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
    Schema::dropIfExists(TableConstants::SITES);
  }
};
