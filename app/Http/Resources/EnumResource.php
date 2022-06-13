<?php

namespace App\Http\Resources;

use App\Models\BaseEnum;
use Illuminate\Http\Resources\Json\JsonResource;

class EnumResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var BaseEnum $this */
        return [
            'value' => $this->value,
            'label' => $this->getLabel(),
        ];
    }
}
