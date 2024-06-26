<?php

/**
 * Converters helper methods
 */
$bn = ['১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯', '০'];
$en = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '0'];

if (! function_exists('bn2en')) {
    function bn2en($number): array|string
    {
        return str_replace($bn, $en, $number);
    }
}
if (! function_exists('en2bn')) {
    function en2bn($number): array|string
    {
        return str_replace($en, $bn, $number);
    }
}
if (! function_exists('format_in_text')) {
    function format_in_text($number): string
    {
        $fmt = new NumberFormatter('bn_BDT', NumberFormatter::SPELLOUT);
        // return \ucwords($fmt->format($number)); // Ten Million Two Thousand Three Hundred Forty-five Point Eight Nine
        return \ucfirst($fmt->format($number)); // Ten million two thousand three hundred forty-five point eight nine
    }
}
if (! function_exists('format_in_bdt')) {
    function format_in_bdt($number): array|string
    {
        $fmt = new NumberFormatter('bn_BDT', NumberFormatter::DECIMAL);

        return bn2en($fmt->format($number));
    }
}
if (! function_exists('format_in_bdt_bn')) {
    function format_in_bdt_bn($number): array|string
    {
        $fmt = new NumberFormatter('bn_BDT', NumberFormatter::DECIMAL);

        return en2bn($fmt->format($number));
    }
}
