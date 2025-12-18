<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public $resetUrl;

    /**
     * Create a new message instance.
     */
    public function __construct($resetUrl)
    {
        $this->resetUrl = $resetUrl;
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this->subject('Password Reset Request - Caffe Arabica')
                    ->view('emails.password-reset')
                    ->with([
                        'resetUrl' => $this->resetUrl,
                    ]);
    }
}