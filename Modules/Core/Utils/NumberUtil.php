<?php

namespace Modules\Core\Utils;

class NumberUtil
{
    public static function format($number)
    {
        return number_format($number, 0, '.', ',');
    }
}
