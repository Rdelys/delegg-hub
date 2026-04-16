<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('devis', function (Blueprint $table) {
            $table->id();

            // CLIENT
            $table->unsignedBigInteger('client_id');
            $table->string('tiime_id')->nullable();

            // INFOS
            $table->string('label')->nullable();
            $table->date('date_emission');
            $table->date('date_validite');
            $table->text('note')->nullable();

            // LIVRAISON
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->nullable();

            // TVA
            $table->string('tva_exoneration')->nullable();

            // 🔥 LIGNES JSON
            $table->json('lines')->nullable();

            // TOTALS
            $table->decimal('total_ht', 10, 2)->default(0);
            $table->decimal('total_tva', 10, 2)->default(0);
            $table->decimal('total_ttc', 10, 2)->default(0);

            $table->timestamps();

            $table->foreign('client_id')
                ->references('id')
                ->on('clients_invoice')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('devis');
    }
};
