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
        $table->boolean('exported_to_lead')->default(false);
        $table->timestamp('exported_at')->nullable();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('google_places', function (Blueprint $table) {
            //
        });
    }
};
