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
            $table->boolean('confirmed');
            $table->date('publish_date');
            $table->dateTimeTz('approved_at');
            $table->decimal('amount', 5, 2);
            $table->double('column', 15, 8);
            $table->enum('choices', ['foo', 'bar']);
            $table->text('description');
            $table->unsignedTinyInteger('votes');
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
