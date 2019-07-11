<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $type, $name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($type, $name = null)
    {
        $this->type = $type;
        $this->name = $name ?? $type;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
//        $address = 'Goldengate (hk) ' . env('MAIL_FROM_ADDRESS') ?? 'noreply@goldengatehk.com';
        $address = 'noreply@goldengatehk.com';
        $name = "Golden Gate (hk)";
        $subject = 'Golden Gate (hk)| Welcome';

        switch (strtolower($this->type)) {
            case 'member':
                return $this->view('emails.email-welcome-member')
                    ->from($address, $name)
                    ->bcc($address, $name)
                    ->replyTo($address, $name)
                    ->subject($subject)
                    ->with('full_name', $this->name);
                break;
            case 'customer':
                return $this->view('emails.email-welcome-customer')
                    ->from($address, $name)
                    ->bcc($address, $name)
                    ->replyTo($address, $name)
                    ->subject($subject)
                    ->with('full_name', $this->name);
                break;
            case 'merchant':
                return $this->view('emails.email-welcome-merchant')
                    ->from($address, $name)
                    ->bcc($address, $name)
                    ->replyTo($address, $name)
                    ->subject($subject)
                    ->with('full_name', $this->name);

                break;
            default;
                break;
        }
    }
}
