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
    Schema::create(TableConstants::FEEDBACK_MEGA_CRMS, function (Blueprint $table) {
      $table->id();
      $table->foreignId(AttributeName::SITE_ID)
        ->index()
        ->constrained(TableConstants::SITES)
        ->cascadeOnUpdate()
        ->restrictOnDelete();

      $table->string(AttributeName::TOKEN);
      $table->dateTime(AttributeName::DOWNLOAD_AT)->nullable();
      $table->dateTime(AttributeName::LAST_REQUEST_AT)->nullable();
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
    Schema::dropIfExists(TableConstants::FEEDBACK_MEGA_CRMS);
  }
};
