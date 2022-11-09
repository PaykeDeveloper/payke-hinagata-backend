<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $lang = $this->getPreferredLanguage($request, ['en', 'ja']);
        if ($lang) {
            App::setLocale($lang);
        }
        return $next($request);
    }

    /**
     * @see \Symfony\Component\HttpFoundation\Request::getPreferredLanguage
     */
    public function getPreferredLanguage(Request $request, array $locales): ?string
    {
        $preferredLanguages = $request->getLanguages();

        if (empty($locales)) {
            return null;
        }

        if (!$preferredLanguages) {
            return null;
        }

        $extendedPreferredLanguages = [];
        foreach ($preferredLanguages as $language) {
            $extendedPreferredLanguages[] = $language;
            if (false !== $position = strpos($language, '_')) {
                $superLanguage = substr($language, 0, $position);
                if (!\in_array($superLanguage, $preferredLanguages)) {
                    $extendedPreferredLanguages[] = $superLanguage;
                }
            }
        }

        $preferredLanguages = array_values(array_intersect($extendedPreferredLanguages, $locales));

        return $preferredLanguages[0] ?? null;
    }
}
