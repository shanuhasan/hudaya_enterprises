<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Product categories (Lowers, Jackets). Each row drives one category page
 * (/products/{slug}) plus its tile on Home and its showcase on /products.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 80);                    // short name on cards: "Lowers"
            $table->string('slug', 80)->unique();          // URL + quote-form value: "lowers"

            // Tile (home) + showcase (products page)
            $table->string('title');                       // "Lowers &"
            $table->string('title_em')->nullable();        // "Track Pants" (italic part)
            $table->text('summary')->nullable();           // showcase paragraph
            $table->json('highlights')->nullable();        // home tile list: ["Track pants", "Joggers", …]
            $table->string('card_image')->nullable();
            $table->string('card_image_alt')->nullable();

            // Category page
            $table->string('hero_eyebrow')->nullable();    // "Collection 01"
            $table->string('hero_title');
            $table->string('hero_title_em')->nullable();
            $table->string('hero_lead', 500)->nullable();
            $table->string('hero_image')->nullable();
            $table->string('intro_title');
            $table->string('intro_title_em')->nullable();
            $table->text('intro_text')->nullable();
            $table->string('intro_image')->nullable();
            $table->string('intro_image_alt')->nullable();
            $table->string('intro_image_position', 5)->default('right'); // left | right
            $table->string('types_title');
            $table->string('types_title_em')->nullable();
            $table->json('specs')->nullable();             // [{"label": "Fabric", "value": "…"}]
            $table->string('cta_eyebrow')->nullable();
            $table->string('cta_title')->nullable();
            $table->string('cta_title_em')->nullable();
            $table->string('cta_text', 500)->nullable();

            $table->string('meta_title');
            $table->string('meta_description', 300);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
