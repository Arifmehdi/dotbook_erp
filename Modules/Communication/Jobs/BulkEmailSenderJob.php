<?php

namespace Modules\Communication\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class BulkEmailSenderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $addresses;

    public $mailObject;

    public function __construct(string|array $addresses, $mailObject)
    {
        $this->addresses = $addresses;
        $this->mailObject = $mailObject;
    }

    public function handle()
    {
        if (is_array($this->addresses) && count($this->addresses) > 0) {
            \Log::info($this->addresses);
            foreach ($this->addresses as $address) {
                Mail::to($address)->send($this->mailObject);
            }
        }

        if (is_string($this->addresses)) {
            \Log::info($this->addresses);
            Mail::to($this->addresses)->send($this->mailObject);
        }
    }
}
