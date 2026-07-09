<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    protected $token;

    public function __construct()
    {
        // Token bisa diset di .env dengan nama FONNTE_TOKEN
        // Jika tidak ada, gunakan string kosong agar tidak error
        $this->token = env('FONNTE_TOKEN', '');
    }

    /**
     * Send WhatsApp Message via Fonnte API
     *
     * @param string $target Phone number
     * @param string $message Message to send
     * @return bool|array
     */
    public function sendMessage($target, $message)
    {
        if (empty($this->token)) {
            Log::warning('Fonnte token is not set. WhatsApp message not sent.');
            return false;
        }

        if (empty($target)) {
            Log::warning('WhatsApp target is empty. Message not sent.');
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])->post('https://api.fonnte.com/send', [
                'target' => $target,
                'message' => $message,
                'countryCode' => '62', // Default Indonesia
            ]);

            if ($response->successful()) {
                return $response->json();
            } else {
                Log::error('Fonnte API Error: ' . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Fonnte Exception: ' . $e->getMessage());
            return false;
        }
    }
}
