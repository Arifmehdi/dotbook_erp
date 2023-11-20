<?php

namespace Modules\Core\Utils;

class BanglaConverter
{
    public static $bn = ['১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯', '০', 'শত', 'হাজার', 'লক্ষ', 'মিলিয়ন', 'বিলিয়ন', 'কোটি'];

    public static $en = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '0',  'hundred', 'thousand', 'lakh', 'million', 'billion', 'crore'];

    public static function bn2en($number)
    {
        return str_replace(self::$bn, self::$en, $number);
    }

    public static function en2bn($number)
    {
        return str_replace(self::$en, self::$bn, $number);
    }
}
