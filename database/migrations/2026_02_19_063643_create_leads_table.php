<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | IDENTITÉ
            |--------------------------------------------------------------------------
            */
            $table->string('prenom_nom');
            $table->string('nom_global')->nullable();
            $table->text('commentaire')->nullable();

            /*
            |--------------------------------------------------------------------------
            | STATUT & PIPELINE
            |--------------------------------------------------------------------------
            */
            $table->enum('chaleur', ['Froid','Tiède','Chaud'])->nullable();
            $table->string('status')->nullable();
            $table->string('status_relance')->nullable();
            $table->string('enfants_percent')->nullable(); // 0%, 40%, etc
            $table->date('date_statut')->nullable();

            /*
            |--------------------------------------------------------------------------
            | CANAUX CONTACT
            |--------------------------------------------------------------------------
            */
            $table->string('linkedin_status')->nullable();
            $table->string('suivi_mail')->nullable();
            $table->string('suivi_whatsapp')->nullable();
            $table->string('appel_tel')->nullable();
            $table->string('mp_instagram')->nullable();
            $table->boolean('follow_insta')->default(false);
            $table->string('com_instagram')->nullable();
            $table->string('formulaire_site')->nullable();
            $table->string('messenger')->nullable();

            /*
            |--------------------------------------------------------------------------
            | INFOS ENTREPRISE
            |--------------------------------------------------------------------------
            */
            $table->string('entreprise')->nullable();
            $table->string('fonction')->nullable();

            /*
            |--------------------------------------------------------------------------
            | CONTACT
            |--------------------------------------------------------------------------
            */
            $table->string('email')->nullable();
            $table->string('tel_fixe')->nullable();
            $table->string('portable')->nullable();

            /*
            |--------------------------------------------------------------------------
            | URL
            |--------------------------------------------------------------------------
            */
            $table->string('url_linkedin')->nullable();
            $table->string('url_maps')->nullable();
            $table->string('url_site')->nullable();
            $table->string('compte_insta')->nullable();

            /*
            |--------------------------------------------------------------------------
            | COMMERCIAL
            |--------------------------------------------------------------------------
            */
            $table->string('devis')->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | INDEX OPTIMISATION
            |--------------------------------------------------------------------------
            */
            $table->index('prenom_nom');
            $table->index('nom_global');
            $table->index('status');
            $table->index('chaleur');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
