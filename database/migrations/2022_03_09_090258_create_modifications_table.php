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
    Schema::create(TableConstants::MODIFICATIONS, function (Blueprint $table) {
      $table->id();
      $table->string(AttributeName::NAME);

      $table->foreignId(AttributeName::GENERATION_ID)
        ->index()
        ->constrained(TableConstants::GENERATIONS)
        ->cascadeOnUpdate()
        ->restrictOnDelete();

      $table->foreignId(AttributeName::BODY_TYPE_ID)
        ->index()
        ->constrained(TableConstants::BODY_TYPES)
        ->cascadeOnUpdate()
        ->restrictOnDelete();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists(TableConstants::MODIFICATIONS);
  }
};
