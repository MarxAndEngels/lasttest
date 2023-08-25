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
        Schema::create(TableConstants::SEO_TAGS, function (Blueprint $table) {
          $table->id();
          $table->foreignId(AttributeName::SITE_ID)
            ->unique()
            ->constrained(TableConstants::SITES)
            ->cascadeOnUpdate()
            ->cascadeOnDelete();
          $table->json(AttributeName::SEO_TAG)->nullable();
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
        Schema::dropIfExists(TableConstants::SEO_TAGS);
    }
};
