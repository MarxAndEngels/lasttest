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
    Schema::create(TableConstants::TELEGRAM_CHANNEL_SITES, function (Blueprint $table) {
      $table->id();
      $table->foreignId(AttributeName::SITE_ID)
        ->index()
        ->constrained(TableConstants::SITES)
        ->cascadeOnUpdate()
        ->restrictOnDelete();

      $table->string(AttributeName::TG_API_KEY, 100);
      $table->string(AttributeName::TG_CHAT_ID, 100);
      $table->text(AttributeName::BODY)->nullable();
      $table->json(AttributeName::FILTER)->nullable();
      $table->boolean(AttributeName::IS_ACTIVE)->index()->default(0);
      $table->dateTime(AttributeName::SEND_AT)->nullable();
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
    Schema::dropIfExists(TableConstants::TELEGRAM_CHANNEL_SITES);
  }
};
