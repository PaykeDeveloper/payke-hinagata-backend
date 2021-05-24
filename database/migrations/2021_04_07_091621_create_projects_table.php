<?php

// FIXME: SAMPLE CODE

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
            $table->string('name');
            $table->boolean('confirmed')->nullable();
            $table->date('publish_date')->nullable();
            $table->dateTimeTz('approved_at')->nullable();
            $table->decimal('amount', 3, 1)->nullable();
            $table->double('column', 10, 3)->nullable();
            $table->enum('choices', \App\Models\Sample\FooBar::all())->nullable();
            $table->text('description');
            $table->unsignedTinyInteger('votes')->nullable();
            $table->uuid('slug')->unique();
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
