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
    Schema::create(TableConstants::DEALERS, function (Blueprint $table) {
      $table->id();
      $table->integer(AttributeName::EXTERNAL_ID)->unique();
      $table->string(AttributeName::TITLE, '40');
      $table->string(AttributeName::SLUG, '40');
      $table->string(AttributeName::CITY, '40')->nullable();
      $table->string(AttributeName::ADDRESS, '80')->nullable();
      $table->string(AttributeName::METRO, '80')->nullable();
      $table->string(AttributeName::SCHEDULE, '80')->nullable();
      $table->string(AttributeName::PHONE, '18')->nullable();
      $table->string(AttributeName::COORDINATES, '25')->nullable();
      $table->string(AttributeName::YOUTUBE_PLAYLIST_REVIEW, '100')->nullable();
      $table->string(AttributeName::SITE, '40')->nullable();
      $table->float(AttributeName::RATING, 2, 1)->nullable();
      $table->string(AttributeName::SHORT_DESCRIPTION)->nullable();
      $table->text(AttributeName::DESCRIPTION)->nullable();
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
    Schema::dropIfExists(TableConstants::DEALERS);
  }
};
