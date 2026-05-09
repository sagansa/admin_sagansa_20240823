<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $this->syncUser($user, 'created');
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        $this->syncUser($user, 'updated');
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        $this->syncUser($user, 'deleted');
    }

    /**
     * Send user data to external webhooks.
     */
    protected function syncUser(User $user, string $action): void
    {
        $webhooks = env('SYNC_USER_WEBHOOKS');
        
        if (empty($webhooks)) {
            return;
        }

        $urlList = explode(',', $webhooks);
        $payload = [
            'action' => $action,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'updated_at' => $user->updated_at,
            ],
            'timestamp' => now()->toIso8601String(),
            'source' => 'SAGANSA_ADMIN'
        ];

        foreach ($urlList as $url) {
            $url = trim($url);
            if (empty($url)) continue;

            // Kita gunakan dispatch after response agar tidak menghambat user di admin
            dispatch(function () use ($url, $payload) {
                try {
                    $response = Http::timeout(5)->post($url, $payload);
                    
                    if (!$response->successful()) {
                        Log::error("Webhook Sync User Gagal ke {$url}", [
                            'status' => $response->status(),
                            'response' => $response->body()
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::error("Koneksi Webhook Sync User Error ke {$url}: " . $e->getMessage());
                }
            })->afterResponse();
        }
    }
}
