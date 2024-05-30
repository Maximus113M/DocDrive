<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;
    protected $password;
    protected $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(String $password, User $user)
    {
        $this->password = $password;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.VerifyEmail')
                    ->subject('Verificación de correo electrónico')
                    ->with([
                        "password" => $this->password,
                        "email" => $this->user->email,
                        "name" => $this->user->name
                    ]);
    }
}
