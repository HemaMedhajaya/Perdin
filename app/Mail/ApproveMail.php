<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApproveMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $statusTampil;
    public $data;
    public $jabatan;
    public $statuskirim;

    /**
     * Create a new message instance.
     */
    public function __construct($name, $statusTampil, $data, $jabatan, $statuskirim)
    {
        $this->name = $name;
        $this->statusTampil = $statusTampil;
        $this->data = $data;
        $this->jabatan = $jabatan;
        $this->statuskirim = $$statuskirim;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Update Status Perjalanan Dinas')
                    ->view('emails.approvestatus')
                    ->with([
                        'name' => $this->name,
                        'status' => $this->statusTampil,
                        'project' => $this->data->name_project,
                        'jabatan' => $this->jabatan,
                        'statuskirim' => $this->statuskirim,
                    ]);
    }

}
