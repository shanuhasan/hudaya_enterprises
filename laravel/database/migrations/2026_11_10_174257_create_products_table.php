<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Example styles (enquiry-led, no prices).
 * featured_position: null = not featured; 1, 2, 3… = order in "Featured styles"
 * (Home shows the first 8, the Products page the first 4).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->string('name', 120);
            $table->string('slug', 140)->unique();
            $table->string('type_label', 80)->nullable();  // card meta: "Men's · Sports"
            $table->string('image')->nullable();
            $table->string('image_alt')->nullable();
            $table->unsignedSmallInteger('featured_position')->nullable()->index();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['category_id', 'is_active', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
