<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\Common\LocaleType;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @response [
 * {"label":"Japanese","value":"jp"}
 * ]
 *
 * @package App\Http\Controllers
 */
class LocaleController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $options = LocaleType::options();
        return response($options);
    }
}
