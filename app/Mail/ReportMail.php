<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReportMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $subject;
    public $view;
    public $body;
    public $file;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $view, $body, $file)
    {
        $this->subject = $subject;
        $this->view = $view;
        $this->body = $body;
        $this->file = $file;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail = $this->subject($this->subject)
            ->markdown(
                $this->view, 
                $this->body
            );

        foreach ($this->file as $filePath) {
            $mail->attach($filePath);
        }

        return $mail;
    }
}
