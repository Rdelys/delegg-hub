<?php
// database/migrations/2026_02_14_200037_update_google_places_add_contact_fields.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('google_places', function (Blueprint $table) {
            // Vérifier et ajouter email si n'existe pas
            if (!Schema::hasColumn('google_places', 'email')) {
                $table->string('email')->nullable()->after('website');
            }
            
            // Vérifier et ajouter facebook si n'existe pas
            if (!Schema::hasColumn('google_places', 'facebook')) {
                $table->string('facebook')->nullable()->after('email');
            }
            
            // Vérifier et ajouter instagram si n'existe pas
            if (!Schema::hasColumn('google_places', 'instagram')) {
                $table->string('instagram')->nullable()->after('facebook');
            }
            
            // Vérifier et ajouter linkedin si n'existe pas
            if (!Schema::hasColumn('google_places', 'linkedin')) {
                $table->string('linkedin')->nullable()->after('instagram');
            }
            
            // Vérifier et ajouter source_url si n'existe pas
            if (!Schema::hasColumn('google_places', 'source_url')) {
                $table->string('source_url')->nullable()->after('linkedin');
            }
            
            // Vérifier et ajouter contact_scraped_at si n'existe pas
            if (!Schema::hasColumn('google_places', 'contact_scraped_at')) {
                $table->timestamp('contact_scraped_at')->nullable()->after('website_scraped_at');
            }
        });
    }

    public function down()
    {
        Schema::table('google_places', function (Blueprint $table) {
            $columns = [];
            
            if (Schema::hasColumn('google_places', 'email')) {
                $columns[] = 'email';
            }
            if (Schema::hasColumn('google_places', 'facebook')) {
                $columns[] = 'facebook';
            }
            if (Schema::hasColumn('google_places', 'instagram')) {
                $columns[] = 'instagram';
            }
            if (Schema::hasColumn('google_places', 'linkedin')) {
                $columns[] = 'linkedin';
            }
            if (Schema::hasColumn('google_places', 'source_url')) {
                $columns[] = 'source_url';
            }
            if (Schema::hasColumn('google_places', 'contact_scraped_at')) {
                $columns[] = 'contact_scraped_at';
            }
            
            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};