<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use \App\Constants\TableConstants;
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
    Schema::create(TableConstants::STORY_CONTENTS, function (Blueprint $table) {
      $table->id();
      $table->string(AttributeName::TITLE, 180);
      $table->string(AttributeName::BODY)->nullable();
      $table->string(AttributeName::BUTTON_COLOR, 30)->nullable();
      $table->string(AttributeName::BUTTON_LINK, 30)->nullable();
      $table->string(AttributeName::BUTTON_TITLE, 180)->nullable();
      $table->boolean(AttributeName::IS_ACTIVE)->default(0)->index();

      $table->foreignId(AttributeName::STORY_ID)
        ->index()
        ->constrained(TableConstants::STORIES)
        ->cascadeOnUpdate()
        ->cascadeOnDelete();

      $table->unsignedMediumInteger(AttributeName::ORDER_COLUMN)->default(9999)->nullable()->index();

      $table->index([AttributeName::ORDER_COLUMN, AttributeName::STORY_ID, AttributeName::IS_ACTIVE]);
      $table->nullableTimestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists(TableConstants::STORY_CONTENTS);
  }
};
