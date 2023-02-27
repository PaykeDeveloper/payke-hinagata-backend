<?php

namespace App\Http\Resources;

use App\Models\BaseEnum;
use Illuminate\Http\Resources\Json\JsonResource;

class EnumResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var BaseEnum $enum */
        $enum = $this->resource;
        return [
            'value' => $enum->value,
            'label' => $enum->getLabel(),
        ];
    }
}
