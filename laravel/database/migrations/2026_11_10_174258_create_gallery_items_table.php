<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Gallery images. group = filter tab (App\Enums\GalleryGroup).
 * home_position: null = not on Home; 1–5 = slot in the Home "Inside the work" mosaic.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gallery_items', function (Blueprint $table) {
            $table->id();
            $table->string('caption');                     // describes the subject, never claims ownership
            $table->string('short_caption', 60)->nullable(); // Home mosaic label (falls back to caption)
            $table->string('group', 20)->index();
            $table->string('image');
            $table->string('image_alt');
            $table->string('aspect', 5)->default('4/5');   // 4/5 | 3/4 | 4/3 | 1/1
            $table->unsignedTinyInteger('home_position')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gallery_items');
    }
};
