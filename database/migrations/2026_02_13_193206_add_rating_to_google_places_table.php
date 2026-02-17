<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('google_places', function (Blueprint $table) {
            $table->decimal('rating', 2, 1)->nullable()->after('website');
            $table->integer('reviews_count')->nullable()->after('rating');
        });
    }

    public function down(): void
    {
        Schema::table('google_places', function (Blueprint $table) {
            $table->dropColumn(['rating','reviews_count']);
        });
    }
};

