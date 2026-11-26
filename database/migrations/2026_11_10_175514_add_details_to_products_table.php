<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Product details managed in the admin (Phase 3). All optional — never invented in seed data. */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->text('description')->nullable()->after('type_label');
            $table->string('material', 160)->nullable()->after('description');
            $table->json('sizes')->nullable()->after('material');           // ["S", "M", "L"]
            $table->string('moq', 80)->nullable()->after('sizes');           // minimum order quantity, free text: "300 pcs per style"
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['description', 'material', 'sizes', 'moq']);
        });
    }
};
