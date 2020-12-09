<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ExportOrdersMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var string
     */
    public $exportFileName;

    /**
     * @var Carbon
     */
    public $exportTime;

    /**
     * Create a new message instance.
     *
     * @param string $exportFileName
     * @param Carbon $exportTime
     */
    public function __construct(string $exportFileName, Carbon $exportTime)
    {
        $this->exportFileName = $exportFileName;
        $this->exportTime = $exportTime;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): self
    {
        return $this
            ->to(config('export.mail'))
            ->subject('Выгрузка заказов')
            ->attach($this->exportFileName)
            ->view('emails.orders_export');
    }
}
