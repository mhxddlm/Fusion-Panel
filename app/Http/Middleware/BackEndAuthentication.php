<?php

namespace App\Http\Middleware;

use App\Node;
use Closure;
use Illuminate\Support\Facades\Log;

class BackEndAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
	    $node = Node::getNodeInfoByNid($request['node']);
    	if($node['real_ip'] != $request->ip()){
    		return abort(403, 'IP record mismatch, connection refused');
	    }
	    if($node['key'] != $request->header('key')){
		    return abort(403, 'Secret key record mismatch, connection refused');
	    }
        return $next($request);
    }
}
