<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Model;
use QCod\ImageUp\Exceptions\InvalidUploadFieldException;
use QCod\ImageUp\HasImageUploads as BaseTrait;

/**
 * @mixin Model
 */
trait HasImageUploads
{
    use BaseTrait;

    /**
     * @param string $field_key
     * @param null $default
     * @return string|null
     */
    public function optionalImageUrl(string $field_key, $default = null): ?string
    {
        try {
            return $this->getAttribute($field_key) ? $this->imageUrl($field_key) : $default;
        } catch (InvalidUploadFieldException $e) {
            report($e);
            return $default;
        }
    }
}
