<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PassRecoveryEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $data, $url;

    public function __construct($data, $url)
    {
        $this->data = $data;
        $this->url = $url;
    }

    public function build()
    {
        $message = $this->url . '/' . base64_encode('u=' . $this->data->user_name . '&t=' . (Carbon::now()->addDays(7)->toDateString()));
        $address = 'noreply@goldengatehk.com';
        $name = env('APP_NAME');
        $subject = env('APP_NAME') . ' | Password Recovery ';
        return $this->view('emails.pass-recovery')
            ->from($address, $name)
//            ->cc($address, $name)
            ->bcc($address, $name)
            ->replyTo($address, $name)
            ->subject($subject)
            ->with('link', $message);
    }
}
