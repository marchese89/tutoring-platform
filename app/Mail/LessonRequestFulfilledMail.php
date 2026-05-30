<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class LessonRequestFulfilledMail extends Mailable
{
    public function build()
    {
        return $this->subject('Richiesta Evasa')
            ->view('emails.lesson-request-fulfilled');
    }
}
