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
    Schema::create(TableConstants::FOLDERS, function (Blueprint $table) {
      $table->id();
      $table->string(AttributeName::TITLE);
      $table->string(AttributeName::TITLE_RUS)->nullable();
      $table->string(AttributeName::SLUG)->index();

      $table->unique([
        AttributeName::MARK_ID, AttributeName::SLUG
      ]);

      $table->foreignId(AttributeName::MARK_ID)
        ->index()
        ->constrained(TableConstants::MARKS)
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
    Schema::dropIfExists(TableConstants::FOLDERS);
  }
};
