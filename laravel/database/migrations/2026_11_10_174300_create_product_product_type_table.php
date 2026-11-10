<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** A style can belong to several types (e.g. "mens" and "sports") — drives the filter tabs. */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_product_type', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_type_id')->constrained()->cascadeOnDelete();
            $table->primary(['product_id', 'product_type_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_product_type');
    }
};
