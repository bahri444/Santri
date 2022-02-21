<?php

namespace App\Http\Middleware;

use App\Models\AksesMenu;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class roleAkses
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
        $data = AksesMenu::join('menu as m', 'akses_menu.menu_id', '=', 'm.id')
            ->where('akses_menu.role_id', Auth::user()->role_id)
            ->orderBy('m.urutan', 'ASC')
            ->get()->toArray();
        $segment = $request->segment(2) ?? '';
        if (in_array($segment, array_column($data, 'link'))) {
            return $next($request);
        } else {
            $data2 = AksesMenu::join('menu as m', 'akses_menu.menu_id', '=', 'm.id_parent')
                ->where('akses_menu.role_id', Auth::user()->role_id)
                ->orderBy('m.urutan', 'ASC')
                ->get()->toArray();
            if (in_array($segment, array_column($data2, 'link'))) {
                return $next($request);
            } else {
                return redirect()->route('dashboard');
            }
        }
    }
}
