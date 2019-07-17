<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Config;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $data, $url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $url)
    {
        $this->data = $data;
        $this->url = $url;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $message = [
            'url' => $this->url . '/' . base64_encode('e=' . $this->data->email . '&i=' . $this->data->id),
            'name' => $this->data->name
        ];

        $address = 'noreply@padashala.com';
        $name = Config::get('app.name');
        $subject = Config::get('app.name') . ' | Email Verification ';
        return $this->view('emails.email-verification')
            ->from($address, $name)
            ->bcc($address, $name)
            ->replyTo($address, $name)
            ->subject($subject)
            ->with('link', $message);
    }
}
