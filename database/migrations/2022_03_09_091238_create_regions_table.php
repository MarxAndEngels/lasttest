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
    Schema::create(TableConstants::REGIONS, function (Blueprint $table) {
      $table->id();
      $table->unsignedMediumInteger(AttributeName::ORDER_COLUMN)->default(9999)->index();
      $table->string(AttributeName::TITLE);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists(TableConstants::REGIONS);
  }
};
