<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * SEO + hero content for the fixed pages (home, about, products, manufacturing,
 * quality, gallery, contact). Category pages keep theirs on the categories table.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('key', 40)->unique();           // matches the route name
            $table->string('meta_title');
            $table->string('meta_description', 300);
            $table->string('hero_eyebrow')->nullable();
            $table->string('hero_title')->nullable();
            $table->string('hero_title_em')->nullable();
            $table->string('hero_title_line2')->nullable(); // optional second headline line (home)
            $table->string('hero_lead', 500)->nullable();
            $table->string('hero_image')->nullable();      // path under public/ or storage
            $table->string('hero_image_alt')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
