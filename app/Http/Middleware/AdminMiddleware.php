<?php

namespace App\Http\Middleware;

use App\Models\Rol;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminMiddleware
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
        
        $user = Auth::user();
        $rol = Rol::toBase()->where('id',$user->idRol)->first();
        if ($rol->rol == 'Admin') {
            return $next($request);
        }
        
        Auth::logout();
        // return redirect('login')->withErrors('INGRESAR CON UN USUARIO ADMI');

    }
}
