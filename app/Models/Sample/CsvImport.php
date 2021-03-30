<?php

namespace App\Models\Sample;

use App\Models\User;
use App\Models\Sample\CsvImportStatus;
use App\Models\Traits\HasImageUploads;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * @mixin IdeHelperCsvImport
 */
class CsvImport extends Model
{
    use HasFactory;
    use HasImageUploads;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];
    protected static $fileFields = [
        'file_name_system' => [
            // TODO: 一旦ローカル環境を参照する形で実装。S3などの共通ディスク要考慮
            'disk' => 'local',
            'path' => 'import-csvs',
        ],
    ];
    protected $hidden = ['file_name_system', 'csv_type'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function createWithUser(UploadedFile $csv_file, User $user): CsvImport
    {
        $csvImport = new self();
        $csvImport->user_id = $user->id;
        $csvImport->file_name_original = $csv_file->getClientOriginalName();
        $csvImport->import_status = CsvImportStatus::WAITING;
        $csvImport->uploadFile($csv_file, 'file_name_system');
        $csvImport->save();
        return $csvImport;
    }

    public function getUplodedFileFullPath(): ?string
    {
        // TODO: 一旦ローカル環境を参照する形で実装。S3などの共通ディスク要考慮
        $path = storage_path('app/'. $this->file_name_system);
        return $path;
    }
}
