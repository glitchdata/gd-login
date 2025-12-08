<?php

namespace App\Services;

use App\Models\EventLog;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class EventLogger
{
    public static function log(string $type, ?int $userId = null, array $context = []): EventLog
    {
        // If migrations haven't been run yet (e.g., fresh env), avoid hard failures on auth flows.
        if (! Schema::hasTable('event_logs')) {
            Log::warning('event_logs table missing; skipped event log write', [
                'type' => $type,
                'user_id' => $userId,
            ]);

            return new EventLog([
                'user_id' => $userId,
                'type' => $type,
                'context' => $context,
            ]);
        }

        return EventLog::create([
            'user_id' => $userId,
            'type' => $type,
            'context' => $context,
        ]);
    }
}
