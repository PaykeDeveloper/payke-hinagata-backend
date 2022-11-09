<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

/**
 * @mixin Model
 */
trait OptimisticLocking
{
    use HasFactory;

    protected bool $lock = true;

    /**
     * @throws ValidationException
     *
     * @see Model::performUpdate()
     */
    protected function performUpdate(Builder $query): bool
    {
        if ($this->fireModelEvent('updating') === false) {
            return false;
        }

        if ($this->usesTimestamps()) {
            $this->updateTimestamps();
        }

        $dirty = $this->getDirty();

        if (count($dirty) > 0) {
            // 追加処理 START
            $versionColumn = static::lockVersionColumn();
            $currentVersion = $this->currentLockVersion();
            if ($this->lockingEnabled()) {
                $query->where($versionColumn, '=', $currentVersion);
            }

            $newVersion = $currentVersion + 1;
            $this->setAttribute($versionColumn, $newVersion);
            $dirty[$versionColumn] = $newVersion;
            // 追加処理 END

            $affected = $this->setKeysForSaveQuery($query)->update($dirty);

            // 追加処理 START
            if ($affected === 0) {
                $this->setAttribute($versionColumn, $currentVersion);
                throw ValidationException::withMessages([self::lockVersionColumn() => __('validation.locking')]);
            }
            // 追加処理 END

            $this->syncChanges();

            $this->fireModelEvent('updated', false);
        }

        return true;
    }

    protected static function lockVersionColumn(): string
    {
        return 'lock_version';
    }

    public function currentLockVersion(): int
    {
        return $this->getAttribute(static::lockVersionColumn());
    }

    protected function lockingEnabled(): bool
    {
        return $this->lock;
    }

    protected function disableLocking(): static
    {
        $this->lock = false;
        return $this;
    }

    public function enableLocking(): static
    {
        $this->lock = true;
        return $this;
    }
}
