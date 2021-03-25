<?php

namespace App\Models\Sample;

use App\Models\User;
use App\Models\Sample\CsvImportStatus;
use App\Models\Traits\HasImageUploads;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

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
            'disk' => 'local', // FIXME: 外部公開されていないS3などのworkerサーバとの共通ディスクに配置する
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
}
