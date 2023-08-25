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
    Schema::create(TableConstants::GENERATIONS, function (Blueprint $table) {
      $table->id();
      $table->string(AttributeName::NAME)->default('I');
      $table->year(AttributeName::YEAR_BEGIN);
      $table->year(AttributeName::YEAR_END)->nullable();
      $table->string(AttributeName::SLUG)->nullable();

      $table->foreignId(AttributeName::FOLDER_ID)
        ->index()
        ->constrained(TableConstants::FOLDERS)
        ->cascadeOnUpdate()
        ->restrictOnDelete();
      $table->unique([
        AttributeName::FOLDER_ID,
        AttributeName::SLUG
      ]);
    });

  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists(TableConstants::GENERATIONS);
  }
};
