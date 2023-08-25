<?php

use App\Constants\Attributes\AttributeName;
use App\Constants\TableConstants;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create(TableConstants::DOMAINS, function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger(AttributeName::EXTERNAL_ID)->unique();
      $table->string(AttributeName::FQDN);
      $table->dateTime(AttributeName::DATE_ADD)->nullable();
      $table->dateTime(AttributeName::DATE_REGISTER)->nullable();
      $table->dateTime(AttributeName::DATE_EXPIRE)->nullable();
      $table->boolean(AttributeName::IS_ACTIVE)->default(true);
      $table->boolean(AttributeName::AVAILABLE)->default(true);
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
    Schema::dropIfExists(TableConstants::DOMAINS);
  }
};
