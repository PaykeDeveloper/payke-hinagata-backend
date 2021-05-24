<?php

// FIXME: SAMPLE CODE

use App\Models\Sample\Priority;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('division_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->uuid('slug')->unique();
            $table->string('name');
            $table->text('description');
            $table->enum('priority', Priority::all())->nullable();
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

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
