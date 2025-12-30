<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Cache;

class RateLimitService
{
    protected int $maxAttempts = 5;
    protected int $decaySeconds = 60;

    public function tooManyAttempts(int $userId): bool
    {
        $key = $this->key($userId);

        $attempts = Cache::get($key, 0);

        if ($attempts >= $this->maxAttempts) {
            return true;
        }

        Cache::put($key, $attempts + 1, $this->decaySeconds);

        return false;
    }

    protected function key(int $userId): string
    {
        return "notifications:user:{$userId}";
    }
    
}
