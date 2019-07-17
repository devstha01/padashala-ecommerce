<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Config;

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
        $message = [
            'url' => $this->url . '/' . base64_encode('u=' . $this->data->user_name . '&t=' . (Carbon::now()->addDays(7)->toDateString())),
            'name' => $this->data->name
        ];
        $address = 'noreply@padashala.com';
        $name = Config::get('app.name');
        $subject = Config::get('app.name') . ' | Password Recovery ';
        return $this->view('emails.pass-recovery')
            ->from($address, $name)
//            ->cc($address, $name)
            ->bcc($address, $name)
            ->replyTo($address, $name)
            ->subject($subject)
            ->with('link', $message);
    }
}
