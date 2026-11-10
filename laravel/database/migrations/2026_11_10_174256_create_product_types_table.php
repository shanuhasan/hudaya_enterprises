<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Types within a category (Men's, Women's, Joggers, Winter…). Each type is a
 * type card on the category page and a filter tab above its product grid.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('name', 80);                    // card title: "Men's Lowers"
            $table->string('short_name', 40);              // filter tab: "Men's"
            $table->string('slug', 40);                    // filter key: "mens"
            $table->string('description', 300)->nullable();
            $table->string('image')->nullable();
            $table->string('image_alt')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['category_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_types');
    }
};
