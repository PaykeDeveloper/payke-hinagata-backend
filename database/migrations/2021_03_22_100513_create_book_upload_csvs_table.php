<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Sample\CsvImportStatus;
use App\Models\Sample\CsvImportType;

class CreateBookUploadCsvsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('csv_imports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->smallInteger('csv_type')->default(CsvImportType::BOOKS);
            $table->smallInteger('import_status')->default(CsvImportStatus::WAITING);
            $table->string('file_name_original', 255);
            $table->string('file_name_system', 255);
            $table->timestamps();
            $table->index(['csv_type', 'import_status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('csv_imports');
    }
}
