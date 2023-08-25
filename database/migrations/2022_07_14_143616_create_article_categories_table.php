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
    Schema::create(TableConstants::ARTICLE_CATEGORIES, function (Blueprint $table) {
      $table->id();
      $table->unsignedMediumInteger(AttributeName::EXTERNAL_ID);
      $table->string(AttributeName::PAGE_TITLE);
      $table->string(AttributeName::LONG_TITLE)->nullable();
      $table->string(AttributeName::DESCRIPTION)->nullable();
      $table->string(AttributeName::SLUG)->unique();
      $table->string(AttributeName::URL)->unique();

      $table->index(AttributeName::CREATED_AT);

      $table->boolean(AttributeName::URL_OVERRIDE)->default(0);
      $table->boolean(AttributeName::IS_ACTIVE)->default(0);
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
    Schema::dropIfExists(TableConstants::ARTICLE_CATEGORIES);
  }
};
