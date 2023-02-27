<?php

namespace App\Http\Resources;

use App\Models\Common\InvitationStatus;
use App\Models\Common\LocaleType;
use Illuminate\Http\Resources\Json\JsonResource;

class EnumResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var LocaleType|InvitationStatus $enum */
        $enum = $this->resource;
        return [
            'value' => $enum->value,
            'label' => $enum->getLabel(),
        ];
    }
}
