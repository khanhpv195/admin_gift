<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('gifts', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('description')->nullable();
            $table->string('logo')->nullable();
            // foreign key to special_gifts table
            $table->unsignedBigInteger('special_gift_id');
            $table->string('status', 60)->default('published');
            // price range from, to
            $table->integer('price_from');
            $table->integer('price_to');
            $table->timestamps();
        });
        Schema::create('gifts_translations', function (Blueprint $table) {
            $table->string('lang_code');
            $table->foreignId('gifts_id');
            $table->string('name', 255)->nullable();

            $table->primary(['lang_code', 'gifts_id'], 'gifts_translations_primary');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gifts');
        Schema::dropIfExists('gifts_translations');
    }
};
