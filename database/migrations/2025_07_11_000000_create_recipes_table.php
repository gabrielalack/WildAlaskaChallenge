<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name', 100);
            $table->string('slug', 255)->unique();
            $table->text('description')->nullable();

            $table->foreign('user_id')->references('id')->on('users');
            $table->index('name');
            $table->index('slug');
            $table->fulltext('description');
        });

        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->text('name');

            $table->fulltext('name');
        });

        Schema::create('recipes_ingredients', function (Blueprint $table) {
            $table->unsignedBigInteger('recipe_id');
            $table->unsignedBigInteger('ingredient_id');

            $table->foreign('recipe_id')->references('id')->on('recipes');
            $table->foreign('ingredient_id')->references('id')->on('ingredients');
        });

        Schema::create('recipe_steps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('recipe_id');
            $table->unsignedInteger('order');
            $table->text('step_description');

            $table->foreign('recipe_id')->references('id')->on('recipes');
            $table->fulltext('step_description');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('recipe_steps');
        Schema::dropIfExists('recipes_ingredients');
        Schema::dropIfExists('ingredients');
        Schema::dropIfExists('recipes');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }

};
