<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            // Remove as colunas antigas
            $table->dropColumn(['sender', 'receiver']);

            // Adiciona as foreign keys
            $table->unsignedBigInteger('sender_id');
            $table->unsignedBigInteger('receiver_id');

            // Define os relacionamentos (assumindo que a tabela users tem uma PK id)
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('receiver_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            // Remove as foreign keys e as colunas novas
            $table->dropForeign(['sender_id']);
            $table->dropForeign(['receiver_id']);
            $table->dropColumn(['sender_id', 'receiver_id']);

            // Recria as colunas antigas
            $table->string('sender');
            $table->string('receiver');
        });
    }
};

