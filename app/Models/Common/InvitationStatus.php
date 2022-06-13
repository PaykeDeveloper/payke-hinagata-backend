<?php

namespace App\Models\Common;

use App\Models\BaseEnum;
use Illuminate\Support\Collection;

enum InvitationStatus: string implements BaseEnum
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Denied = 'denied';

    public function getLabel(): string
    {
        return match ($this) {
            self::Pending => __('Pending'),
            self::Approved => __('Approved'),
            self::Denied => __('Denied'),
        };
    }

    public static function getOptions(): Collection
    {
        return collect(self::cases())->map(fn(self $case) => [
            'value' => $case->value,
            'label' => $case->getLabel(),
        ]);
    }
}
