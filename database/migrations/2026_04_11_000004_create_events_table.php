<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('event_type_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->date('event_date')->nullable();
            $table->text('description')->nullable();
            $table->text('message')->nullable();
            $table->string('cover_image')->nullable();
            $table->boolean('gift_registry_enabled')->default(false);
            $table->boolean('public')->default(true);
            $table->decimal('budget', 12, 2)->nullable();
            $table->integer('guest_count')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
