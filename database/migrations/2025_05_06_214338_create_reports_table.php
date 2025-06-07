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
    Schema::create('reports', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('oferta_id');
        $table->unsignedBigInteger('zglaszajacy_id'); // kto zgłasza
        $table->unsignedBigInteger('zglaszany_id');   // właściciel oferty
        $table->text('powody')->nullable();
        $table->text('opis')->nullable();
        $table->timestamps();


        $table->text('status')->default('pending'); // pending, accepted, rejected


        $table->foreign('oferta_id')->references('id')->on('oferty')->onDelete('cascade');
        $table->foreign('zglaszajacy_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('zglaszany_id')->references('id')->on('users')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */

    public function down()
{
    Schema::table('reports', function (Blueprint $table) {
        $table->dropColumn('status');
    });
}

};
