<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class isMember
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userId = Auth::id();

        $colocationId = null;

        if ($request->route('colocation')) {
            $routeParam = $request->route('colocation');
            if (is_object($routeParam)) {
                $colocationId = $routeParam->id;
            }
            else {
                $colocationId = $routeParam;
            }
        }
        else if ($request->route('colocation_id')) {
            $colocationId = $request->route('colocation_id');
        }
        else if ($request->route('id')) {
            $colocationId = $request->route('id');
        }

        if (!$colocationId) {
            return $next($request);
        }

        $isMember = false;
        $memberships = \App\Models\ColocationMember::where('colocation_id', $colocationId)
            ->where('user_id', $userId)
            ->get();

        foreach ($memberships as $membership) {
            if ($membership->left_at == null) {
                $isMember = true;
                break;
            }
        }

        if (!$isMember) {
            return redirect()->route('user.dashboard')->with('error', 'Vous n\'êtes pas membre de cette colocation.');
        }

        return $next($request);
    }
}