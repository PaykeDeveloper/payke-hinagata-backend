<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\Common\LocaleType;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @response [
 * {"label":"日本語","value":"ja"}
 * ]
 */
class LocaleController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $options = LocaleType::options();
        return response($options);
    }
}
