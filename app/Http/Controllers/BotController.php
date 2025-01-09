<?php

namespace App\Http\Controllers;

use App\Mail\SendMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BotController extends Controller
{
    public function store(int $chatId, string $text, $replyMarkup = null)
    {
        $token = "https://api.telegram.org/bot7557830081:AAHv39YSNxAS6i3DvrlCKP7Zm0NYc7nV2oo";
        $payload = [
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => 'HTML',
        ];

        if ($replyMarkup) {
            $payload['reply_markup'] = json_encode($replyMarkup);
        }

        Http::post($token . '/sendMessage', $payload);
    }

    public function bot(Request $request)
    {
        try {
            $data = $request->all();
            $chat_id = $data['message']['chat']['id'];
            $text = $data['message']['text'] ?? null;
            $photo = $data['message']['photo'] ?? null;

            if ($text === '/start') {
                $this->store($chat_id, "Assalomu alaykum! Iltimos, tanlang:", [
                    'keyboard' => [
                        [
                            ['text' => 'Register'],
                            ['text' => 'Login']
                        ]
                    ],
                    'resize_keyboard' => true,
                    'one_time_keyboard' => true,
                ]);
                return;
            }

            if ($text === 'Register') {
                Cache::put("register_step_{$chat_id}", 'name');
                $this->store($chat_id, "Iltimos, ismingizni kiriting:");
                return;
            }

            if (Cache::get("register_step_{$chat_id}") === 'name') {
                Cache::put("register_name_{$chat_id}", $text);
                Cache::put("register_step_{$chat_id}", 'email');
                $this->store($chat_id, "Iltimos, elektron pochta manzilingizni kiriting:");
                return;
            }

            if (Cache::get("register_step_{$chat_id}") === 'email') {
                Cache::put("register_email_{$chat_id}", $text);
                Cache::put("register_step_{$chat_id}", 'password');
                $this->store($chat_id, "Iltimos, parolingizni kiriting:");
                return;
            }

            if (Cache::get("register_step_{$chat_id}") === 'password') {
                Cache::put("register_password_{$chat_id}", $text);
                Cache::put("register_step_{$chat_id}", 'confirmation_code');

                $confirmation_code = Str::random(6);

                $email = Cache::get("register_email_{$chat_id}");
                $name = Cache::get("register_name_{$chat_id}");

                try {
                    Mail::to($email)->send(new SendMessage($name, $confirmation_code));
                    Log::info('Email sent successfully');
                    $this->store($chat_id, "Emailizga tasdiqlash kodi yuborildi. Iltimos, uni kiriting.");
                } catch (\Exception $e) {
                    Log::error('Email sending failed: ' . $e->getMessage());
                    $this->store($chat_id, "Tasdiqlash kodi yuborishda xatolik yuz berdi. Iltimos, qaytadan urinib ko'ring.");
                }

                Cache::put("confirmation_code_{$chat_id}", $confirmation_code);
                return;
            }

            if (Cache::get("register_step_{$chat_id}") === 'confirmation_code') {
                if ($text === Cache::get("confirmation_code_{$chat_id}")) {
                    Cache::put("register_password_{$chat_id}", bcrypt(Cache::get("register_password_{$chat_id}")));
                    Cache::put("register_step_{$chat_id}", 'image');
                    $this->store($chat_id, "Tasdiqlash kodi to'g'ri. Iltimos, profilingiz uchun rasm yuboring.");
                    Cache::forget("confirmation_code_{$chat_id}");
                } else {
                    $this->store($chat_id, "Tasdiqlash kodi noto'g'ri. Iltimos, to'g'ri kodi kiriting.");
                }
                return;
            }

            if (Cache::get("register_step_{$chat_id}") === 'image') {
                if ($photo) {
                    $file_id = end($photo)['file_id'];

                    $telegram_api = "https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN');
                    $file_path_response = file_get_contents("{$telegram_api}/getFile?file_id={$file_id}");
                    $response = json_decode($file_path_response, true);

                    if (isset($response['result']['file_path'])) {
                        $file_path = $response['result']['file_path'];
                        $download_url = "https://api.telegram.org/file/bot" . env('TELEGRAM_BOT_TOKEN') . "/{$file_path}";

                        $image_name = uniqid() . '.jpg';
                        $image_content = file_get_contents($download_url);

                        if ($image_content) {
                            Storage::disk('public')->put("uploads/{$image_name}", $image_content);
                            $image_path = "uploads/{$image_name}";
                        } else {
                            $this->store($chat_id, "Rasmni yuklab olishda xatolik yuz berdi. Iltimos, qaytadan urinib ko'ring.");
                            return;
                        }
                        $user = User::create([
                            'name' => Cache::get("register_name_{$chat_id}"),
                            'email' => Cache::get("register_email_{$chat_id}"),
                            'password' => Cache::get("register_password_{$chat_id}"),
                            'chat_id' => $chat_id,
                            'image' => "uploads/{$image_name}",
                        ]);

                        $this->store($chat_id, "Siz muvaffaqiyatli ro'yxatdan o'tdingiz!");

                        Cache::forget("register_step_{$chat_id}");
                        Cache::forget("register_name_{$chat_id}");
                        Cache::forget("register_email_{$chat_id}");
                        Cache::forget("register_password_{$chat_id}");
                        Cache::forget("confirmation_code_{$chat_id}");
                    } else {
                        $this->store($chat_id, "Rasmni yuklab olishda muammo yuz berdi. Iltimos, qaytadan urinib ko'ring.");
                    }
                } else {
                    $this->store($chat_id, "Iltimos, rasm yuboring!");
                }
                return;
            }
        } catch (\Exception $exception) {
            Log::error($exception);
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage()
            ]);
        }
    }
}
