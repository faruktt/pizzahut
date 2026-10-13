<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Banners are now image-only (no title/subtitle/link/button text)
        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn(['title', 'subtitle', 'link', 'button_text']);
        });
    }

    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->string('title')->after('id');
            $table->string('subtitle')->nullable()->after('title');
            $table->string('link')->nullable()->after('image');
            $table->string('button_text')->nullable()->after('link');
        });
    }
};
