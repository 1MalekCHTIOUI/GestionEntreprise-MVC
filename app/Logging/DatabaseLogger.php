<?php

namespace App\Logging;

use Monolog\Logger;
use Monolog\LogRecord;
use Illuminate\Support\Facades\DB;
use Monolog\Handler\AbstractProcessingHandler;

class DatabaseLogger extends AbstractProcessingHandler
{
    protected function write(LogRecord $record): void
    {
        // Log only error level and above
        if ($record->level->value >= \Monolog\Level::Error->value) {
            DB::table('logs')->insert([
                'message' => $record->message,
                'level' => $record->level->name,
                'context' => json_encode($record->context),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
