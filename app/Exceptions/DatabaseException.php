<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DatabaseException extends Exception
{
    public function report()
    {
        // Log the exception to the database using DB facade
        DB::table('exception_logs')->insert([
            'message' => $this->getMessage(),
            'code' => $this->getCode(),
            'file' => $this->getFile(),
            'line' => $this->getLine(),
            'trace' => $this->getTraceAsString(),
            'created_at' => now(),
        ]);

        // Log to the default log files using Log facade
        Log::error('DatabaseException: ' . $this->getMessage(), [
            'code' => $this->getCode(),
            'file' => $this->getFile(),
            'line' => $this->getLine(),
            'trace' => $this->getTraceAsString(),
        ]);
    }
}
