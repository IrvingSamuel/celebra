<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supplier_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('company_name');
            $table->text('description')->nullable();
            $table->string('phone')->nullable();
            $table->string('city')->nullable();
            $table->string('state', 2)->nullable();
            $table->foreignId('service_category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('logo')->nullable();
            $table->boolean('verified')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supplier_profiles');
    }
};
