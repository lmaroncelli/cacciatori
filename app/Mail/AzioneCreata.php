<?php

namespace App\Mail;

use App\Zona;
use App\AzioneCaccia;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AzioneCreata extends Mailable
{
    use Queueable, SerializesModels;

    //any public property defined on your mailable class will automatically be made available to the view.
    public $azione;
    public $zona;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(AzioneCaccia $azione, Zona $zona)
    {
        $this->azione = $azione;
        $this->zona = $zona;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
                ->from('luigi@info-alberghi.com')
                ->markdown('emails.azione.creata');
    }
}
