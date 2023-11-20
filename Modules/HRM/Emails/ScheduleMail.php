<?php

namespace Modules\HRM\Emails;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ScheduleMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $applicant;

    public $email_template;

    public $InterviewSchedule;

    public function __construct($applicant, $email_template, $InterviewSchedule)
    {
        $this->InterviewSchedule = $InterviewSchedule;
        $this->applicant = $applicant;
        $this->email_template = $email_template;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $str = $this->email_template->body_format;
        $schedule_date = $this->InterviewSchedule->date_time;
        $schedule_date = Carbon::parse($schedule_date)->format('F j, Y, g:iA');
        $str = str_replace('$name', $this->applicant->full_name, $str);
        $str = str_replace('$message', $this->InterviewSchedule['description'], $str);
        $str = str_replace('$date', $schedule_date, $str);
        $this->email_template->body_format = $str;

        // dd($this->email_template->body_format);
        return $this
            ->subject($this->email_template->subject)
            ->view('hrm::recruitments.emails.for-schedule-mail');
    }
}
