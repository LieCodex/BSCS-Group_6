<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // <-- Added missing import
use App\Models\ChatMessage;
use App\Models\Conversation;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException; // <-- Added for better error handling

class WebhookController extends Controller
{
    /**
     * Handles an incoming webhook request to send a message and broadcast it.
     */
    public function handleReverbMessage(Request $request)
    {
        // ----------------------------------------------------
        // 🛑 1. CRITICAL SECURITY CHECK (Implement this fully!)
        // ----------------------------------------------------

        // NOTE: This logic is a placeholder. You MUST implement logic to verify
        // the request signature (e.g., HMAC-SHA256 hash of the payload) against
        // a secret key shared with the webhook sender.
        
        $secretKey = env('WEBHOOK_MESSAGE_SECRET', 'a-secure-default-key');
        
        // Example: Check for a custom header if the sender supports it
        if ($request->header('X-App-Secret') !== $secretKey) {
             // Log::warning('Unauthorized webhook access attempt.', ['ip' => $request->ip()]);
             // return response()->json(['message' => 'Unauthorized: Invalid secret key or signature missing.'], 401);
        }

        // ----------------------------------------------------
        // 2. Data Validation and Database Integrity
        // ----------------------------------------------------
        try {
            $data = $request->validate([
                // FIX: Use 'exists:users,id' to ensure both users exist
                'sender_id' => 'required|integer|exists:users,id',
                'receiver_id' => 'required|integer|exists:users,id',
                'message' => 'nullable|string',
                'image_base64' => 'nullable|string',
                'conversation_id' => 'nullable|integer'
            ]);
        } catch (ValidationException $e) {
            Log::error('Webhook Validation Failed.', $e->errors());
            return response()->json(['message' => 'Validation Failed', 'errors' => $e->errors()], 422); // Unprocessable Entity
        }

        // 3. Data Integrity: Prevent saving empty messages
        if (empty($data['message']) && empty($data['image_base64'])) {
             return response()->json(['message' => 'Nothing to send. Message or image content is required.'], 400); // Bad Request
        }

        $imageUrl = null;

        // ----------------------------------------------------
        // 4. Handle Image Upload and Path Fix
        // ----------------------------------------------------
        if (!empty($data['image_base64'])) {
            try {
                $imageContent = base64_decode($data['image_base64']);
                if ($imageContent === false) {
                     throw new \Exception("Invalid base64 encoding.");
                }

                // Detect MIME type and determine extension
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mimeType = finfo_buffer($finfo, $imageContent);
                finfo_close($finfo);

                $extension = match($mimeType) {
                    'image/jpeg' => 'jpg',
                    'image/png' => 'png',
                    'image/gif' => 'gif',
                    'image/webp' => 'webp',
                    default => throw new \Exception("Unsupported MIME type: {$mimeType}"),
                };

                $filename = Str::random(12) . '_' . time() . '.' . $extension;
                $fullPath = 'post_images/' . $filename; // Define the full path for Storage

                // Use the defined path for putting the file
                Storage::disk('spaces')->put($fullPath, $imageContent, ['visibility' => 'public']);

                // Get the URL using the full path
                $imageUrl = Storage::disk('spaces')->url($fullPath);

            } catch (\Exception $e) {
                Log::error('Webhook image upload failed: ' . $e->getMessage());
                return response()->json(['message' => 'Image processing failed.'], 400);
            }
        }

        // ----------------------------------------------------
        // 5. Conversation Handling
        // ----------------------------------------------------
        // Use Conversation::find for existing or fallback to firstOrCreate based on receiver_id
        $conversation = Conversation::find($data['conversation_id']);

        if (!$conversation) {
            // NOTE: This assumes your Conversation model design uses 'user_id' to store one of the participants.
            $conversation = Conversation::firstOrCreate([
                'user_id' => $data['receiver_id']
            ]);
        }

        // ----------------------------------------------------
        // 6. Save Message and Broadcast
        // ----------------------------------------------------
        $message = ChatMessage::create([
            'sender_id'       => $data['sender_id'],
            'receiver_id'     => $data['receiver_id'],
            'message'         => $data['message'] ?? '',
            'image_path'      => $imageUrl,
            'conversation_id' => $conversation->id,
            'is_ai'           => true, // Assuming messages from webhooks are system/AI messages
        ]);

        // Broadcast to receiver, triggering the Livewire listener
        broadcast(new MessageSent($message)); 

        return response()->json([
            'success' => true,
            'message' => 'Message received and broadcasted.',
            'data' => [
                'id' => $message->id,
                'conversation_id' => $message->conversation_id,
                'image_path' => $message->image_path
            ]
        ], 200);
    }
}