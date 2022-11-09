<?php

// FIXME: SAMPLE CODE

use App\Models\Sample\Priority;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('division_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('member_id')->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->uuid('slug')->unique();
            $table->string('name');
            $table->text('description');
            $table->enum('priority', [
                Priority::High->value,
                Priority::Middle->value,
                Priority::Low->value,
            ])->nullable();
            $table->boolean('approved')->nullable();
            $table->date('start_date')->nullable();
            $table->dateTimeTz('finished_at')->nullable();
            $table->unsignedTinyInteger('difficulty')->nullable();
            $table->decimal('coefficient', 3, 1)->nullable();
            $table->double('productivity', 10, 3)->nullable();
            $table->integer('lock_version')->unsigned();
            $table->timestampsTz();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('projects');
    }
};
