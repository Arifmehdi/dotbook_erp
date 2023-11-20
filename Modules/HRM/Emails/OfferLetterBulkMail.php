<?php

namespace Modules\HRM\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OfferLetterBulkMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $participants;

    public $subject;

    public $message;

    public $color;

    public function __construct($participants, $subject, $message, $color)
    {
        $this->participants = $participants;
        $this->subject = $subject;
        $this->message = $message;
        $this->color = $color;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('Offer Letter')
            ->view('hrm::recruitments.emails.for-offer-letter');
    }
}
