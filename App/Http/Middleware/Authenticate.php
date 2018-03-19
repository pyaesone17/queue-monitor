<?php
namespace Pyaesone17\QueueMonitor\App\Http\Middleware;
use Pyaesone17\Modules\QueueMonitor\QueueMonitor;

class Authenticate
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response
     */
    public function handle($request, $next)
    {
        return QueueMonitor::check($request) ? $next($request) : abort(403);
    }
}