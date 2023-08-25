<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use \App\Constants\TableConstants;
use App\Constants\Attributes\AttributeName;

return new class extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create(TableConstants::SETS, function (Blueprint $table) {
      $table->id();
      $table->string(AttributeName::TITLE);
      $table->string(AttributeName::SLUG)->unique();
      $table->json(AttributeName::FILTER)->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists(TableConstants::SETS);
  }
};
