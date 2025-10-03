<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EnsurePayrollEnabled
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (! payrollEnabled()) {
            throw new NotFoundHttpException('Payroll module is currently disabled.');
        }

        return $next($request);
    }
}
