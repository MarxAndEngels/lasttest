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
    Schema::create(TableConstants::BANKS, function (Blueprint $table) {
      $table->id();
      $table->string(AttributeName::NAME);
      $table->string(AttributeName::TITLE)->nullable();
      $table->string(AttributeName::SLUG)->unique();
      $table->string(AttributeName::LICENSE_TITLE, 50)->nullable();
      $table->boolean(AttributeName::IS_ACTIVE)->default(0)->index();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists(TableConstants::BANKS);
  }
};
