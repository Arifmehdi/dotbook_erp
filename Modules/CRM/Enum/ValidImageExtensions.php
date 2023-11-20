<?php

namespace Modules\CRM\Enum;

enum ValidImageExtensions: string
{
    case PNG = 'png';
    case JPG = 'jpg';
    case JPEG = 'jpeg';
    case ICO = 'ico';
    case GIF = 'gif';
    case PDF = 'pdf';
}
