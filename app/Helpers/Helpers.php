<?php

namespace App\Helpers;

use Throwable;
use App\Models\ErrorLog;
use App\Models\RequestLog;
use Illuminate\Support\Facades\Auth;

class Helpers
{
    public static function log_error_to_db(Throwable $e): void
    {
        ErrorLog::create([
            'message' => $e->getMessage(),
            'file'    => $e->getFile(),
            'line'    => $e->getLine(),
            'trace'   => $e->getTraceAsString(),
            'user_id' => Auth::id(),
        ]);
    }

    public static function log_request_to_db(): void
    {
        $request = request();

        RequestLog::create([
            'method'      => $request->method(),
            'url'         => $request->fullUrl(),
            'ip'          => $request->ip(),
            'user_agent'  => $request->userAgent(),
            'user_id'     => Auth::id(),
        ]);
    }
}

