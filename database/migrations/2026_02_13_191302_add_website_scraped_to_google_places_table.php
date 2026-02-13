<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('google_places', function (Blueprint $table) {
        $table->boolean('website_scraped')->default(false)->after('website');
        $table->timestamp('website_scraped_at')->nullable();
    });
}

public function down()
{
    Schema::table('google_places', function (Blueprint $table) {
        $table->dropColumn(['website_scraped','website_scraped_at']);
    });
}

};
