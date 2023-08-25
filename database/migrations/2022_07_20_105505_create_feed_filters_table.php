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
    Schema::create(TableConstants::FEED_FILTERS, function (Blueprint $table) {
      $table->id();

      $table->string(AttributeName::NAME, 100);

      $table->json(AttributeName::FILTER)->nullable();

      $table->foreignId(AttributeName::SITE_ID)
        ->index()
        ->constrained(TableConstants::SITES)
        ->cascadeOnUpdate()
        ->cascadeOnDelete();

      $table->dateTime(AttributeName::DOWNLOAD_AT)->nullable();

      $table->boolean(AttributeName::GENERATE_FILE)->default(false);
      $table->boolean(AttributeName::FEED_YANDEX_YML)->default(false);
      $table->boolean(AttributeName::FEED_YANDEX_XML)->default(false);
      $table->boolean(AttributeName::FEED_VK_XML)->default(false);
      $table->dateTime(AttributeName::GENERATE_FILE_AT)->nullable();
      $table->timestamps();

      $table->unique([AttributeName::SITE_ID, AttributeName::NAME]);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists(TableConstants::FEED_FILTERS);
  }
};
