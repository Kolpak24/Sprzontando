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
        Schema::create('oferty', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // powiązanie z użytkownikiem
            $table->string('tytul');
            $table->text('opis');
            $table->string('lokalizacja');
            $table->decimal('cena', 10, 2);
            $table->timestamps();
            $table->string('rodzaj')->nullable();
            $table->string('status')->default('pending');
            $table->string('obraz')->nullable();
            $table->json('applicants')->nullable();

            // relacja z tabelą users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('oferty', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        
         Schema::table('oferty', function (Blueprint $table) {
        $table->dropColumn('applicants');
        });
    }
};
