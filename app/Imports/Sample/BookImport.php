<?php

namespace App\Imports\Sample;

use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeImport;
use Maatwebsite\Excel\Events\AfterImport;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\User;
use App\Models\Sample\Book;
use App\Models\Sample\CsvImport;
use App\Models\Sample\CsvImportStatus;
use DB;

class BookImport implements
    WithEvents,
    WithCustomCsvSettings,
    WithHeadingRow,
    ToModel,
    WithValidation,
    WithChunkReading,
    ShouldQueue,
    SkipsOnFailure
{
    public $timeout = 60 * 5;
    private $id = null;
    private $user_id = null;

    public function __construct($id)
    {
        $this->id = $id;
        $csv_import = CsvImport::firstWhere('id', $this->id);
        $this->user_id = $csv_import->user_id;
    }

    public function registerEvents(): array
    {
        return [
            BeforeImport::class => function (BeforeImport $event) {
                DB::transaction(function () {
                    $csv_import = CsvImport::firstWhere('id', $this->id);
                    $csv_import->fill([
                        'import_status' => CsvImportStatus::RUNNING,
                    ])->save();
                });
            },
            AfterImport::class => function (AfterImport $event) {
                DB::transaction(function () {
                    $csv_import = CsvImport::firstWhere('id', $this->id);
                    if ($csv_import->import_status == CsvImportStatus::FAILED) {
                        return;
                    }
                    $csv_import->fill([
                        'import_status' => CsvImportStatus::SUCCESS,
                    ])->save();
                });
            },
        ];
    }

    public function onFailure(Failure ...$failures)
    {
        // XXX: 当該クラスはSkipsOnFailureを指定しているためエラーを無視し後続の取り込みは継続する。エラー行の情報などを出力しフィードバックするとより親切。
        DB::transaction(function () {
            $csv_import = CsvImport::firstWhere('id', $this->id);
            $csv_import->fill([
                'import_status' => CsvImportStatus::FAILED,
            ])->save();
        });
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:20'],
            'author' => ['nullable', 'string'],
            'release_date' => ['nullable', 'date'],
        ];
    }

    public function model(array $row)
    {
        // XXX: サンプルのため登録のみに対応。csvファイル上でユニークキーなどを指定して登録/更新を判別できると理想的
        DB::transaction(function () use ($row) {
            Book::createWithUser($row, User::firstWhere('id', $this->user_id));
        });
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function getCsvSettings(): array
    {
        return [
            'input_encoding' => 'UTF-8'
        ];
    }
}
