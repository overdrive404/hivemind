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
        Schema::create('friends', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Кто отправил заявку
            $table->foreignId('friend_id')->constrained('users')->onDelete('cascade'); // Кому отправлена
            $table->enum('status', ['pending', 'accepted', 'declined'])->default('pending'); // Статус заявки
            $table->timestamps();

            $table->unique(['user_id', 'friend_id']); // Исключаем дубликаты
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('friends');
    }
};
