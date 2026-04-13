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
        Schema::create('venues', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('city');
            $table->string('state');
            $table->string('address')->nullable();
            $table->enum('type', ['indoor', 'outdoor', 'ambos'])->default('indoor');
            $table->decimal('price', 12, 2)->nullable();
            $table->string('image')->nullable();
            $table->json('gallery')->nullable();
            $table->decimal('rating', 3, 2)->default(0);
            $table->integer('capacity')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venues');
    }
};
