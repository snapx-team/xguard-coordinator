<?php

namespace Xguard\Coordinator\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Xguard\Coordinator\Models\Coordinator;

class CheckHasAccess
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $coordinator = Coordinator::where(Coordinator::USER_ID, '=', Auth::user()->id)->first();
            if ($coordinator === null) {
                abort(403, "You need to be added to the coordinator app. Please ask an admin for access.");
            }
        } else {
            abort(403, "Please first login to ERP");
        }
        return $next($request);
    }
}
