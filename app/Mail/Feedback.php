<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Feedback extends Mailable
{
    use Queueable, SerializesModels;

    /** @var User */
    public $user;

    /** @var string */
    public $feedback;

    /** @var string */
    public $email;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, string $message, string $email)
    {
        $this->user = $user;
        $this->feedback = $message;
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Обратная связь')->view('emails.feedback');
    }
}
