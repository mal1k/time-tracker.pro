<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserMail extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        unset($this->data['type'], $this->data['email']);
        if(isset($this->data['subject'])){
            $title = env('APP_NAME').' - '. $this->data['subject'];
            return $this->markdown('mail.default')->subject($title)->with('data', $this->data);
        }
        return $this->markdown('mail.default')->with('data', $this->data);
    }
}
