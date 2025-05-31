<?php

namespace ResolutoDigital\DevChat\Core;

use Illuminate\Support\Facades\Storage;

class HistoryLogger
{

    public function store(string $instruction, array $response): void
    {
        $history = $this->getAll();
        $history[] = [
            'timestamp' => now()->toDateTimeString(),
            'instruction' => $instruction,
            'response' => $response
        ];

        Storage::put(config('devchat.history_path'), json_encode($history, JSON_PRETTY_PRINT));
    }

    public function getAll(): array
    {
        if (!Storage::exists(config('devchat.history_path'))) {
            return [];
        }

        return json_decode(Storage::get(config('devchat.history_path')), true);
    }

}