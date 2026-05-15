<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetCurrentPartner
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user?->isPartner()) {
            return $next($request);
        }

        $id = session('current_partner_id');

        $valid = $id && $user->partners()->where('id', $id)->exists();

        if (! $valid) {
            $first = $user->partners()->first();

            abort_if($first === null, 404);

            session(['current_partner_id' => $first->id]);
        }

        return $next($request);
    }
}
