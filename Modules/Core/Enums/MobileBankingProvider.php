<?php

namespace Modules\Core\Enums;

enum MobileBankingProvider: string
{
    case Bkash = 'Bkash';
    case Nagad = 'Nagad';
    case Rocket = 'Rocket';
    case Upay = 'Upay';
    case Cellfin = 'Cellfin';
    case Mcash = 'Mcash';
}
