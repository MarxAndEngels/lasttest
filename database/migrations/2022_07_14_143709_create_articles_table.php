<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Constants\Attributes\AttributeName;
use App\Constants\TableConstants;

return new class extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create(TableConstants::ARTICLES, function (Blueprint $table) {
      $table->id();
      $table->unsignedMediumInteger(AttributeName::EXTERNAL_ID)->nullable();
      $table->unsignedMediumInteger(AttributeName::VIEWS)->default(0);

      $table->string(AttributeName::PAGE_TITLE);
      $table->string(AttributeName::LONG_TITLE)->nullable();
      $table->string(AttributeName::SHORT_DESCRIPTION)->nullable();
      $table->string(AttributeName::SLUG)->unique();
      $table->string(AttributeName::URL)->nullable();
      $table->string(AttributeName::VIDEO_YOUTUBE, 100)->nullable();

      $table->string(AttributeName::DESCRIPTION)->nullable();
      $table->text(AttributeName::BODY)->nullable();

      $table->boolean(AttributeName::IS_ACTIVE)->default(0);
      $table->boolean(AttributeName::URL_OVERRIDE)->default(0);


      $table->foreignId(AttributeName::ARTICLE_CATEGORY_ID)
        ->constrained(TableConstants::ARTICLE_CATEGORIES)
        ->cascadeOnUpdate()
        ->restrictOnDelete();

      $table->dateTime(AttributeName::PUBLISHED_AT);
      $table->timestamps();

      $table->index([AttributeName::CREATED_AT, AttributeName::ARTICLE_CATEGORY_ID]);
      $table->index([AttributeName::SLUG, AttributeName::ARTICLE_CATEGORY_ID]);
      $table->index([AttributeName::URL, AttributeName::ARTICLE_CATEGORY_ID]);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists(TableConstants::ARTICLES);
  }
};
