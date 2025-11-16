<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    /**
     * Send a prompt to the Gemini API and get a response.
     *
     * @param string $prompt
     * @return string
     */
    public function askGemini($prompt)
    {
        $apiKey = config('services.gemini.api_key');
        
        // Use the model from your config file, defaulting to 'gemini-pro'
        $model = config('services.gemini.model', 'gemini-pro');

        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

        // The payload for 'generateContent' is different from the old 'generateText'
        $payload = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ]
        ];

        try {
            $response = Http::post($url, $payload);

            if ($response->failed()) {
                Log::error('Gemini API request failed: ' . $response->body());
                return "Sorry, I couldn't connect to the AI right now.";
            }

            $json = $response->json();

            // The response structure for 'generateContent' is also different
            $text = $json['candidates'][0]['content']['parts'][0]['text'] ?? null;

            if (empty($text)) {
                Log::warning('Gemini response was empty or in an unexpected format: ' . $response->body());
                return "I'm not sure what to say to that!";
            }

            Log::info('Gemini API success.');
            return $text;

        } catch (\Exception $e) {
            Log::error('Exception in GeminiService: ' . $e->getMessage());
            return "An error occurred while trying to generate a reply.";
        }
    }
}