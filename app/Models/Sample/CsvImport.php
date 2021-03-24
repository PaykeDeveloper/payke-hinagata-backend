<?php

namespace App\Models\Sample;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class CsvImport extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function createWithUser(array $attributes, User $user): CsvImport
    {
        $csvImport = new self();
        $csvImport->fill($attributes);
        $csvImport->user_id = $user->id;
        $csvImport->save();
        return $csvImport;
    }
}
