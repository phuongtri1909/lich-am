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
        Schema::create('meta_tags', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // header, footer
            $table->text('meta_tags')->nullable(); // JSON chứa các meta tags
            $table->text('gtag_code')->nullable(); // Google Analytics/GTM code
            $table->text('custom_scripts')->nullable(); // Custom scripts
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meta_tags');
    }
};