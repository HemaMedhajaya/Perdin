<?php 
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TravelRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $approver;
    public $statusTampil; 
    public $user; 
    public $statuskirim; 

    public function __construct($approver, $statusTampil, $user, $statuskirim)
    {
        $this->approver = $approver;
        $this->statusTampil = $statusTampil;
        $this->user = $user;
        $this->statuskirim = $statuskirim;
    }

    public function build()
    {
        return $this->subject('Approval Perjalanan Dinas')
            ->view('emails.travel_request')
            ->with([
                'name' => $this->user->name,
                'status' => $this->statusTampil, // Gunakan statusTampil yang sudah dimapping
                'project' => $this->approver->name_project,
                'statuskirim' => $this->statuskirim,
            ]);
    }
}
