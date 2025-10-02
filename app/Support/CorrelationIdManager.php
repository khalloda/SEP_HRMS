<?php

namespace App\Support;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CorrelationIdManager
{
    protected ?string $correlationId = null;

    public function set(string $correlationId): string
    {
        $this->correlationId = $correlationId;

        if ($request = request()) {
            $request->attributes->set('correlation_id', $correlationId);
        }

        Log::withContext(['correlation_id' => $correlationId]);

        return $correlationId;
    }

    public function get(): ?string
    {
        if ($this->correlationId) {
            return $this->correlationId;
        }

        $request = request();

        if ($request && $request->attributes->has('correlation_id')) {
            return (string) $request->attributes->get('correlation_id');
        }

        return null;
    }

    public function ensure(): string
    {
        $current = $this->get();

        if ($current) {
            return $current;
        }

        return $this->set((string) Str::uuid());
    }
}
