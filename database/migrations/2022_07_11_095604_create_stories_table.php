<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Constants\TableConstants;
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
    Schema::create(TableConstants::STORIES, function (Blueprint $table) {
      $table->id();

      $table->string(AttributeName::TITLE, 180);


      $table->boolean(AttributeName::IS_ACTIVE)->default(0)->index();

      $table->unsignedMediumInteger(AttributeName::ORDER_COLUMN)->default(9999)->nullable()->index();

      $table->index([AttributeName::ORDER_COLUMN, AttributeName::TITLE, AttributeName::IS_ACTIVE]);

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
    Schema::dropIfExists(TableConstants::STORIES);
  }
};
