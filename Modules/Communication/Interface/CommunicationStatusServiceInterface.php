<?php

namespace Modules\Communication\Interface;

interface CommunicationStatusServiceInterface
{
    public function getCommunicationEmailStatus();

    public function getCommunicationSmsStatus();

    public function getCommunicationWhatsappStatus();
}
