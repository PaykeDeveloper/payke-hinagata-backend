<?php

// FIXME: SAMPLE CODE

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('division_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestampsTz();
            $table->unique(['user_id', 'division_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
