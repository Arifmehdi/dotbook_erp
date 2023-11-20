<?php

namespace Modules\HRM\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InterviewSingleMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $recipient;

    public $email_template;

    public function __construct($recipient, $email_template)
    {
        $this->recipient = $recipient;
        $this->email_template = $email_template;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // dd('ok');
        $str = $this->email_template->body_format;
        $str = str_replace('$name', $this->recipient->full_name, $str);
        $this->email_template->body_format = $str;

        return $this
            ->subject($this->email_template->subject)
            ->view('hrm::recruitments.emails.for-interview-mail');
    }
}
