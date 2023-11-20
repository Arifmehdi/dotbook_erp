<?php

namespace Modules\Communication\Service;

use Modules\Communication\Entities\CommunicationStatus;
use Modules\Communication\Interface\CommunicationStatusServiceInterface;

class CommunicationStatusService implements CommunicationStatusServiceInterface
{
    public function getCommunicationEmailStatus()
    {
        $getInactiveMail = CommunicationStatus::whereNotNull('mail_status')->select('mail_status')->get();

        return $plucked = $getInactiveMail->pluck('mail_status');
    }

    public function getCommunicationSmsStatus()
    {
        $getInactiveMail = CommunicationStatus::whereNotNull('sms_status')->select('sms_status')->get();

        return $plucked = $getInactiveMail->pluck('sms_status');
    }

    public function getCommunicationWhatsappStatus()
    {
        $getInactiveMail = CommunicationStatus::whereNotNull('whatsapp_status')->select('whatsapp_status')->get();

        return $plucked = $getInactiveMail->pluck('whatsapp_status');
    }
}
