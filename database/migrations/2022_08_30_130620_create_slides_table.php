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
    Schema::create(TableConstants::SLIDES, function (Blueprint $table) {
      $table->id();
      $table->string(AttributeName::TITLE, 100);
      $table->string(AttributeName::BODY)->nullable();
      $table->string(AttributeName::LINK, 100);

      $table->boolean(AttributeName::IS_ACTIVE)->default(0)->index();

      $table->unsignedMediumInteger(AttributeName::ORDER_COLUMN)->default(9999)->nullable()->index();

      $table->index([AttributeName::ORDER_COLUMN, AttributeName::IS_ACTIVE]);
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
    Schema::dropIfExists(TableConstants::SLIDES);
  }
};
