<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Bot extends Controller
{
    public function store(int $chatId, string $text)
    {
        $token = "https://api.telegram.org/bot7539995650:AAGg_tR_wi9js1u6OeKJl7bY0wC5sUOVzH0";
        $response = Http::post($token . '/sendMessage', [
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => 'HTML',
        ]);
    }

    public function set(Request $request)
    {
        try {
            $data = $request->all();
            $chat_id = $data['message']['chat']['id'];
            $text = $data['message']['text'];

            if (!Cache::has("game_{$chat_id}")) {
                $randomNumber = rand(1, 100);
                Cache::put("game_{$chat_id}", [
                    'random_number' => $randomNumber,
                    'attempts' => 0,
                ]);
                $this->store($chat_id, "O'yin boshlandi! 1 dan 100 gacha sonni toping.");
            }

            $gameData = Cache::get("game_{$chat_id}");
            $randomNumber = $gameData['random_number'];
            $attempts = $gameData['attempts'];

            if (is_numeric($text) && $text >= 1 && $text <= 100) {
                $attempts++;

                if ($text == $randomNumber) {
                    $this->store($chat_id, "Tabriklaymiz! Siz {$attempts} ta urinishda toptingiz!");
                    Cache::forget("game_{$chat_id}");
                } else {
                    $message = "Noto'g'ri. ";
                    $message .= ($text < $randomNumber) ? "Kiritilgan son kichik." : "Kiritilgan son katta.";
                    $message .= " Yana bir marta urinib ko'ring! Urinish: {$attempts}";

                    $this->store($chat_id, $message);

                    Cache::put("game_{$chat_id}", [
                        'random_number' => $randomNumber,
                        'attempts' => $attempts,
                    ]);
                }
            } else {
                $this->store($chat_id, "Iltimos, faqat 1 va 100 orasidagi sonni kiriting.");
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Message sent successfully'
            ]);
        } catch (\Exception $exception) {
            Log::error($exception);
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage()
            ]);
        }
    }
    public function get(Request $request)
    {
        Log::info($request->all());
        try {
            $data = $request->all();
            $fileId = $data['message']['document']['file_id'] ?? null;

            if (!$fileId) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No file attached.',
                ], 400);
            }

            $token = "7539995650:AAGg_tR_wi9js1u6OeKJl7bY0wC5sUOVzH0";
            $file = Http::get("https://api.telegram.org/bot{$token}/getFile", [
                'file_id' => $fileId,
            ]);

            $filePath = $file->json()['result']['file_path'];
            $fileUrl = "https://api.telegram.org/file/bot{$token}/{$filePath}";

            $fileContents = Http::get($fileUrl);

            $path = 'uploads/' . basename($filePath);
            Storage::disk('public')->put($path, $fileContents->body());

            return response()->json([
                'status' => 'success',
                'message' => 'File downloaded successfully.',
                'file' => [
                    'name' => basename($filePath),
                    'path' => asset('storage/' . $path),
                ],
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error getting file from Telegram.',
            ], 500);
        }
    }
}
