<?php

namespace Modules\Core\Utils;

class IntlUtil
{
    public static function textFormat($number): string
    {
        $formatter = new \NumberFormatter('en', \NumberFormatter::SPELLOUT);

        return $formatter->format($number);
    }
}
