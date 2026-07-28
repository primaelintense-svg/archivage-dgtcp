<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForcerChangementMotDePasse
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->doit_changer_mot_de_passe && ! $request->routeIs('changerMotDePasse.*') && ! $request->routeIs('logout')) {
            return redirect()->route('changerMotDePasse.show');
        }

        return $next($request);
    }
}
