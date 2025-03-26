<?php 
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TravelRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $detail; // Data yang dikirim ke email

    public function __construct($detail)
    {
        $this->detail = $detail;
    }

    public function build()
    {
        return $this->subject('Status Perjalanan Dinas')
                    ->view('emails.travel_request'); // Blade template untuk email
    }
}
