<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Livewire\WithFileUploads;
use Livewire\Component;

class Telegram extends Component
{
    use WithFileUploads;

    public $text;
    public $image;
    public $video;
    public $audio;

    public function render()
    {
        return view('livewire.telegram')->layout('components.layouts.main');
    }

    public function send()
    {
        $users = User::orderBy('id', 'asc')->get();
        $staffList = '';

        $token = "https://api.telegram.org/bot7539995650:AAGg_tR_wi9js1u6OeKJl7bY0wC5sUOVzH0";
        $chatId = 5158120151;

        $data = $this->validate([
            'text' => 'required',
            'image' => 'nullable|mimes:png,jpg|max:5000',
            'video' => 'nullable|mimes:mp4,avi,mkv|max:10000',
            'audio' => 'nullable|mimes:mp3|max:51200',
        ]);
        foreach ($users as $user) {
            $staffList .= "{$user->id}. {$user->name} - {$user->email}\n";
        }
        // Text yuborish
        Http::post($token . '/sendMessage', [
            'parse_mode' => 'HTML',
            'chat_id' => $chatId,
            'text' => "<b>{$this->text}</b>\n\nHodimlar ro'yxati:\n{$staffList}",
        ]);

        // Rasm yuborish
        if ($this->image) {
            $imageFilePath = $this->image->getRealPath();
            Http::attach('photo', file_get_contents($imageFilePath), $this->image->getClientOriginalName())
                ->post($token . '/sendPhoto', [
                    'chat_id' => $chatId,
                    'caption' => '',
                ]);
        }

        // Video yuborish
        if ($this->video) {
            $videoFilePath = $this->video->getRealPath();
            Http::attach('video', file_get_contents($videoFilePath), $this->video->getClientOriginalName())
                ->post($token . '/sendVideo', [
                    'chat_id' => $chatId,
                ]);
        }

        // Audio yuborish
        if ($this->audio) {
            $audioFilePath = $this->audio->getRealPath();
            Http::attach('audio', file_get_contents($audioFilePath), $this->audio->getClientOriginalName())
                ->post($token . '/sendAudio', [
                    'chat_id' => $chatId,
                    'caption' => 'Audio Caption',
                ]);
        }

        $this->text = '';
        $this->image = null;
        $this->video = null;
        $this->audio = null;
        session()->flash('success', 'Message sent successfully!');
    }
}
