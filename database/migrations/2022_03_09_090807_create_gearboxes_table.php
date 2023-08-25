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
    Schema::create(TableConstants::GEARBOXES, function (Blueprint $table) {
      $table->id();
      $table->string(AttributeName::TITLE, 15)->nullable();
      $table->string(AttributeName::NAME, 15)->unique();
      $table->string(AttributeName::TITLE_SHORT, 15)->nullable();
      $table->string(AttributeName::TITLE_SHORT_RUS, 15)->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists(TableConstants::GEARBOXES);
  }
};
