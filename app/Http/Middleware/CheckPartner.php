<?php

namespace App\Http\Middleware;

use App\Models\MasterDeveloperApp;
use App\Models\MasterPartner;
use Closure;
use Illuminate\Http\Request;

class CheckPartner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!session('admin')) {
            $partner = MasterPartner::where('partner_email', session('user'))->first('partner_id');
            $developerApp = MasterDeveloperApp::where('developer_app_name', $request->route('developerAppName'))->first('partner_id');

            if ($developerApp->partner_id != $partner->partner_id) {
                return redirect()->route('dashboard.index');
            } else {
                return $next($request);
            }
        } else {
            return $next($request);
        }
    }
}
