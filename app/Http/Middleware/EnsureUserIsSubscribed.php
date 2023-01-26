<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserIsSubscribed
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->user() && ! $request->user()->subscribed('default')) {
            return redirect('subscription');
        }
        return $next($request);
        
/* サブスクリプション利用者のSubscriptionページ入室制限を実装中 */
        /* if ($request->user() && $request->user()->subscribed('default')) {
            return redirect('basic');
        }
        return $next($request); */
    }
}
