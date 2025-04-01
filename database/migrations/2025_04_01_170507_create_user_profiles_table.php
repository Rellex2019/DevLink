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
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            // Основная информация
            $table->string('avatar')->nullable()->default('default-avatar.jpg');
            $table->text('bio')->nullable();
            $table->string('position')->nullable(); // Должность/роль в команде
            $table->string('website')->nullable();
            $table->string('location')->nullable();
            
            // Социальные сети
            $table->string('github')->nullable();
            $table->string('twitter')->nullable();
            $table->string('linkedin')->nullable();
            
            // Настройки
            $table->boolean('dark_mode')->default(false);
            $table->string('theme_color')->default('blue');
            
            // Статистика
            $table->integer('total_projects')->default(0);
            $table->integer('completed_tasks')->default(0);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
