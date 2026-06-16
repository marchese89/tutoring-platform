<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class LessonRequestFulfilledMail extends Mailable
{
    public function build()
    {
        return $this->subject(__('mail.lesson_request_fulfilled.subject'))
            ->view('emails.lesson-request-fulfilled');
    }
}
