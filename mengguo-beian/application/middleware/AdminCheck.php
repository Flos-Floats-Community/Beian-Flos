<?php
namespace app\middleware;

use think\facade\Session;
use think\Response;

class AdminCheck
{
    public function handle($request, \Closure $next)
    {
        if (!Session::has('admin_id')) {
            return redirect('/admin/flos');
        }
        
        return $next($request);
    }
}