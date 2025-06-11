<?php

namespace Modules\Admin\App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $locale = session('locale', config('app.locale'));

        if (in_array($locale, ['en', 'hu', 'sr'])) {
            app()->setLocale($locale);
        }

        return $next($request);
    }
}

?>