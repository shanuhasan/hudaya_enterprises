<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Company details and site-wide text (replaces static-site/src/site.config.json).
 * Empty value = not set: the related element is hidden or shows a bracketed placeholder.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();             // e.g. whatsapp_number
            $table->text('value')->nullable();
            $table->string('group', 40)->default('general'); // company | contact | social | content
            $table->string('type', 20)->default('text');   // text | textarea | email | tel | url
            $table->string('label');                       // shown in the admin
            $table->string('help')->nullable();            // admin hint, e.g. format
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
