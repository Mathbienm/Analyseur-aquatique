<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LocalOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();

        if (!$this->isLocalIp($ip)) {
            return response('Forbidden', 403);
        }

        return $next($request);
    }

    /**
     * Vérifie si l'adresse IP est locale.
     *
     * @param  string  $ip
     * @return bool
     */
    private function isLocalIp($ip)
    {
        $localIpv4 = ['127.0.0.1'];
        $localIpv6 = ['::1'];

        $privateIpv4Ranges = [
            '10.0.0.0/8',
            '172.16.0.0/12',
            '192.168.0.0/16'
        ];

        if (in_array($ip, $localIpv4) || in_array($ip, $localIpv6)) {
            return true;
        }

        foreach ($privateIpv4Ranges as $range) {
            if ($this->ipInRange($ip, $range)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Vérifie si une adresse IP est dans une plage donnée.
     *
     * @param  string  $ip
     * @param  string  $range
     * @return bool
     */
    private function ipInRange($ip, $range)
    {
        list($subnet, $bits) = explode('/', $range);
        $ip = ip2long($ip);
        $subnet = ip2long($subnet);
        $mask = -1 << (32 - $bits);
        $subnet &= $mask;
        return ($ip & $mask) == $subnet;
    }
}
