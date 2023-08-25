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
    Schema::create(TableConstants::OWNERS, function (Blueprint $table) {
      $table->id();
      $table->tinyInteger(AttributeName::NUMBER);
      $table->string(AttributeName::TITLE);
      $table->string(AttributeName::NAME)->unique();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists(TableConstants::OWNERS);
  }
};
