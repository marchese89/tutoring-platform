<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class NewStudentRequestMail extends Mailable
{
    public function build()
    {
        return $this->subject(__('mail.new_student_request.subject'))
            ->view('emails.new-student-request');
    }
}
