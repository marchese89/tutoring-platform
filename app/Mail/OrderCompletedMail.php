<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Storage;

class OrderCompletedMail extends Mailable
{
    public function __construct(
        public User $user,
        public string $pdf_path,
        public string $data,
        public float $total
    ) {}

    public function build()
    {
        return $this->subject(__('mail.order_completed.subject'))
            ->view('emails.order-success')
            ->with([
                'user' => $this->user,
                'data' => $this->data,
                'total' => $this->total,
            ])
            ->attach(
                Storage::disk('private')->path($this->pdf_path),
                [
                    'as' => __('mail.order_completed.attachment_name'),
                    'mime' => 'application/pdf',
                ]
            );
    }
}
