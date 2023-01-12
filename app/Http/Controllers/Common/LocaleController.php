<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Resources\EnumResource;
use App\Models\Common\LocaleType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * @group Common Locale
 *
 * @response [
 * {
 * "value": "ja",
 * "label": "Japanese"
 * }
 * ]
 */
class LocaleController extends Controller
{
    public function __invoke(Request $request): AnonymousResourceCollection
    {
        $resources = LocaleType::cases();
        return EnumResource::collection($resources);
    }
}
