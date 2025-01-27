<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class authorizePages
{
    private $roleCode = [
        '1' => 'internal-sale',
        '2' => 'sale-admin',
        '3' => 'branch-manager',
        '4' => 'office-chief'
    ];

    public function handle(Request $request, Closure $next)
    {
        $decoded = $request->attributes->get('decoded')->roleCode;

        $currentRoute = $request->route()->getName();
        $routePrefix = explode('.', $currentRoute)[0];

        if ($this->roleCode[$decoded] === $routePrefix) {
            return $next($request);
        }

        switch ($decoded) {
            case '1':
                return redirect()->route('internal-sale.new-tasks');
                break;
            case '2':
                return redirect()->route('sale-admin.new-tasks');
                break;
            case '3':
                return redirect()->route('branch-manager.tasks');
                break;
            case '4':
                return redirect()->route('office-chief.tasks');
                break;
            default:
                return redirect('/');
        }
    }
}