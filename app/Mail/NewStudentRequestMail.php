<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class NewStudentRequestMail extends Mailable
{
    public function build()
    {
        return $this->subject('Nuova Richiesta Studente')
            ->view('emails.new-student-request');
    }
}
