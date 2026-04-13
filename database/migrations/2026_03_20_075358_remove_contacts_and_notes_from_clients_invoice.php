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
        Schema::table('clients_invoice', function (Blueprint $table) {
            // Suppression des colonnes contacts
            $table->dropColumn([
                'contact_firstname',
                'contact_lastname',
                'contact_function',
                'contact_email',
                'contact_phone',
                'notes'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients_invoice', function (Blueprint $table) {
            // Recréation des colonnes en cas de rollback
            $table->json('contact_firstname')->nullable()->after('phone');
            $table->json('contact_lastname')->nullable()->after('contact_firstname');
            $table->json('contact_function')->nullable()->after('contact_lastname');
            $table->json('contact_email')->nullable()->after('contact_function');
            $table->json('contact_phone')->nullable()->after('contact_email');
            $table->text('notes')->nullable()->after('contact_phone');
        });
    }
};