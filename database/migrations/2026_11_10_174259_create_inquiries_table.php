<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Quote / contact requests from the form on /contact. Fields mirror the approved form. */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->string('company', 160);
            $table->string('email', 190);
            $table->string('phone', 40);
            $table->string('country', 80)->nullable();
            $table->string('city', 80)->nullable();
            $table->string('buyer_type', 30);              // App\Enums\BuyerType
            $table->string('category', 20);                // App\Enums\InquiryCategory
            $table->string('product', 160)->nullable();
            $table->string('quantity', 80);
            $table->string('timeline', 20)->nullable();    // App\Enums\Timeline
            $table->json('customisation')->nullable();     // ["Custom fabric", …]
            $table->text('message')->nullable();

            $table->string('status', 20)->default('new')->index(); // App\Enums\InquiryStatus
            $table->text('admin_notes')->nullable();
            $table->timestamp('read_at')->nullable();

            $table->string('source_url', 500)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->timestamps();

            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inquiries');
    }
};
