<?php

namespace Modules\HRM\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InterviewBulkMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $recipients;

    public $email_template;

    public function __construct($recipients, $email_template)
    {
        $this->recipients = $recipients;
        $this->email_template = $email_template;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        foreach ($this->recipients as $recipient) {
            $str = $this->email_template->body_format;
            $str = str_replace('$name', $recipient->full_name, $str);
            $this->email_template->body_format = $str;
        }

        return $this
            ->subject($this->email_template->subject)
            ->view('hrm::recruitments.emails.for-interview-mail');
    }
}
