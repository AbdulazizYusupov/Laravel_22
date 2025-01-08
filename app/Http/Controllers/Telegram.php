<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class Telegram extends Controller
{
    public function index()
    {
        return view('telegram.index');
    }

    public function send(Request $request)
    {
        $token = 'https://api.telegram.org/bot7539995650:AAGg_tR_wi9js1u6OeKJl7bY0wC5sUOVzH0';
        $data = $request->validate([
            'text' => 'required',
        ]);
        $response = Http::post($token . '/sendMessage', [
            'parse_mode' => 'HTML',
            'chat_id' => '5158120151',
            'text' => '<i>' . $data['text'] . '</i>',
            'reply_markup' => json_encode([
                'keyboard' => [
                    [
                        ['text' => 'Option 1'],
                    ],
                    [
                        ['text' => 'Option 2'], ['text' => 'Option 3'],
                    ],
                    [
                        ['text' => 'Option 4'], ['text' => 'Option 5'], ['text' => 'Option 6'],
                    ],
                ],
                'resize_keyboard' => true
            ])
        ]);
        return back()->with('success', 'Message sent successfully');
    }
}
