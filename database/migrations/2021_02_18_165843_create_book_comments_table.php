<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// FIXME: サンプルコードです。
class CreateBookCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_comments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('book_id')->constrained();
            $table->boolean('confirmed')->nullable();
            $table->date('publish_date')->nullable();
            $table->dateTimeTz('approved_at')->nullable();
            $table->decimal('amount', 3, 1)->nullable();
            $table->double('column', 10, 3)->nullable();
            $table->enum('choices', \App\Models\Sample\FooBar::all())->nullable();
            $table->text('description');
            $table->unsignedTinyInteger('votes')->nullable();
            $table->string('slug')->unique();
            $table->string('cover')->nullable();
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
        Schema::dropIfExists('book_comments');
    }
}
