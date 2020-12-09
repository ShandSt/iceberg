<?php

namespace App\Mail;

use App\Http\Requests\Api\ReviseRequest;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderRevise extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $request;

    /**
     * Create a new message instance.
     *
     * @param ReviseRequest $request
     * @param User          $user
     */
    public function __construct(ReviseRequest $request, User $user)
    {
        $this->user     = $user;
        $this->request = $request;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Запрос сверки')
            ->view('emails.order_revise')
            ->with([
                'user'      => $this->user,
                'date_from' => $this->request->get('date_from'),
                'date_to'   => $this->request->get('date_to'),
                'email'     => $this->request->get('email'),
                'inn'       => $this->request->get('inn')
            ]);
    }
}
