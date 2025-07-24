<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class FavoriteWordChanged extends Notification
{
    use Queueable;

    public $word; // Pass the word info here

    public function __construct($word)
    {
        $this->word = $word;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => "Your favorite word '{$this->word->greek_word}' was changed.",
            'word_id' => $this->word->id,
            'greek_word' => $this->word->greek_word,
        ];
    }
}
