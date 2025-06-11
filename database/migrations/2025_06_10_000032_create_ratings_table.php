<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatingsTable extends Migration
{
    public function up()
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Powiązanie z konkretną ofertą (oferty.id)
            $table->unsignedBigInteger('offer_id');

            // Kto wystawił ocenę (właściciel oferty)
            $table->unsignedBigInteger('rating_from_user_id');
            // Kto otrzymał ocenę (wykonawca, czyli chosen_user_id z oferty)
            $table->unsignedBigInteger('rating_to_user_id');

            // Gwiazdki 1–5
            $table->tinyInteger('stars')->unsigned();
            // Komentarz (opcjonalny, max 255 znaków)
            $table->string('comment', 255)->nullable();

            $table->timestamps();

            // Klucze obce
            $table->foreign('offer_id')
                  ->references('id')->on('oferty')
                  ->onDelete('cascade');

            $table->foreign('rating_from_user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');

            $table->foreign('rating_to_user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');

            // Każda oferta może mieć co najwyżej jedną ocenę
            $table->unique('offer_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ratings');
    }
}
