<?php

namespace Modules\Communication\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomerBulkEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $bulkMaixlData;

    public function __construct($mailData)
    {
        $this->bulkMailData = $mailData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $bulkMailData = $this->bulkMailData;

        return $this->view('communication::email.templates.bulk-mail', compact('bulkMailData'));
    }
}
