<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;
    protected $url;
    protected $name;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(String $url, String $name)
    {
        $this->url = $url;
        $this->name = $name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.ResetPassword')
                    ->subject('Cambiar contraseña')
                    ->with([
                        'resetUrl' => $this->url,
                        'name' => $this->name
                    ]);
    }
}
