<style>
    /* Global CSS */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        line-height: 16px !important;
    }

    body {
        font-family: solaimanlipi, sans-serif;
        line-height: 10px !important;
    }

    .small-text {
        font-size: 10px;
    }

    .main {
        box-sizing: border-box;
        width: 100%;
        display: inline-block;
        /* padding: 20px 30px; */
    }

    .wrapper {
        width: 100%;
    }

    .b-font {
        font-size: 10px;
    }

    table,
    th,
    td,
    tr {
        border-collapse: collapse;
    }

    td {
        margin: 0;
        padding: 0;
        vertical-align: top;
    }

    .no-wrap {
        white-space: nowrap;
    }

    .my-1 {
        margin-top: 10px;
        margin-bottom: 10px;
    }

    .my-2 {
        margin-top: 20px;
        margin-bottom: 20px;
    }

    .my-3 {
        margin-top: 30px;
        margin-bottom: 30px;
    }

    .mt-0 {
        margin-top: 0;
    }

    .mr-1 {
        margin-right: 10px;
    }

    .pt-0 {
        padding-top: 0;
    }

    .pr-1 {
        padding-right: 10px;
    }

    .text-center {
        text-align: center;
    }

    .d-flex {
        display: flex;
    }

    .d-block {
        display: block;
        width: 100%;
    }

    .t1 {
        -moz-tab-size: 4;
        tab-size: 4;
    }

    span.tab {
        display: inline-block;
        width: 34px;
    }

    span.relative {
        position: relative;
    }

    span.absolute {
        position: absolute;
        left: 0;
        bottom: 0;
        width: 100%;
    }

    /* Header style */
    #header {
        width: 100%;
    }

    .header_wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .logo img {
        width: 100px;
        margin-top: 5px;
    }

    .company_title {
        /* width: 200px; */
    }

    .text-bold-large {
        font-weight: bolder;
        font-size: 1em;
    }

    h1.company_name {
        white-space: nowrap;
        text-transform: capitalize;
        color: #000;
        display: inline-block;
        font-weight: 600;
        font-size: 18px;
        transform: scale(.9, 1.5);
        margin-top: 5px;
        /* margin-left: -20px;*/
    }

    p.company_desc {
        /* text-align: center; */
        font-size: 10px;
        color: #000;
        /* margin-top: 15px; */
        /* background: #d57706; */
        /* margin-left: -10px; */
    }

    p {
        font-size: 10px;
        color: #000;
    }

    .border_right {
        width: 4px;
        margin: 0 5px;
        background: #737373;
    }

    /* Line One */
    .line_one table tbody,
    .present_address table tbody,
    .permanent_address table tbody,
    .letter_sign table tbody {
        display: block;
        width: 100%;
    }

    .line_one table td {
        font-size: 10px;
        width: 33%;
        display: flex;
        float: left;
    }

    .present_address td,
    .permanent_address td {
        font-size: 10px;
    }

    table.salary td {
        border: 1px solid;
        text-align: center;
        padding: 3px 1px;
    }

    @media print {
        .main {
            margin: 0;
        }

        .page-break {
            page-break-after: auto;
        }

        .page-break-before {
            page-break-before: always;
        }
    }

    .flex-center {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .f-left {
        float: left;
    }

    .f-right {
        float: right;
    }

    .monospace {
        /* font-family: monospace; */
    }

    .small-text {
        font-size: 7px !important;
    }

    .col-6 {
        width: 50%;
        float: left;
    }
    .col-7 {
        width: 60%;
        float: left;
    }
    .col-5 {
        width: 40%;
        float: left;
    }

    .row {
        width: 100%;
        float: left;
    }

    .mt-20 {
        margin-top: 20px;
    }

</style>

@php
function convert_number($number)
{
$my_number = $number;

if (($number < 0) || ($number> 999999999))
    {
    throw new Exception("Number is out of range");
    }
    $Kt = floor($number / 10000000); /* Koti */
    $number -= $Kt * 10000000;
    $Gn = floor($number / 100000); /* lakh */
    $number -= $Gn * 100000;
    $kn = floor($number / 1000); /* Thousands (kilo) */
    $number -= $kn * 1000;
    $Hn = floor($number / 100); /* Hundreds (hecto) */
    $number -= $Hn * 100;
    $Dn = floor($number / 10); /* Tens (deca) */
    $n = $number % 10; /* Ones */

    $res = "";

    if ($Kt)
    {
    $res .= convert_number($Kt) . " Koti ";
    }
    if ($Gn)
    {
    $res .= convert_number($Gn) . " Lakh";
    }

    if ($kn)
    {
    $res .= (empty($res) ? "" : " ") .
    convert_number($kn) . " Thousand";
    }

    if ($Hn)
    {
    $res .= (empty($res) ? "" : " ") .
    convert_number($Hn) . " Hundred";
    }

    $ones = array("", "One", "Two", "Three", "Four", "Five", "Six",
    "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen",
    "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen",
    "Nineteen");
    $tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty",
    "Seventy", "Eigthy", "Ninety");

    if ($Dn || $n)
    {
    if (!empty($res))
    {
    $res .= " and ";
    }

    if ($Dn < 2) { $res .=$ones[$Dn * 10 + $n]; } else { $res .=$tens[$Dn]; if ($n) { $res .="-" . $ones[$n]; } } } if (empty($res)) { $res="zero" ; } return $res; }
    @endphp
        @foreach($employees_ids as $key=> $employee_id)
        <div class="main page-break" style="margin-bottom: 30px">
            <div class="wrapper">
                @include('layout.mpdf.header')
                @php
                $employee = Modules\HRM\Entities\Employee::where('id',$employee_id)->first();

                @endphp
                <h4 class="text-center b-font my-1 text-bold-large"><strong>চাকুরীর নিয়োগপত্র</strong></h4>
                <div class="line_one my-1">
                    <table class="d-block">
                        <tr class="d-block">
                            <td style="width: 33%">নামঃ {{ $employee->name }}</td>
                            <td style="width: 33%">পিতার / স্বামীর নামঃ {{ $employee->father_name }}</td>
                            <td style="width: 33%">মাতার নামঃ {{ $employee->mother_name }} </td>
                        </tr>
                    </table>
                </div><br>

                <div class="present_address mt-1">
                    <table class="d-block">
                        <tr class="d-block">
                            <td><b> বর্তমান ঠিকানাঃ </b></td>
                            <td style="width: 90%;">
                                {{-- <p>গ্রামঃ {{ $employee->present_village ?? 'Has no village'}},
                                    পোষ্টঃ {{ $employee->presentUnion->name ?? 'Has no union'}} ,
                                    উপজেলাঃ {{ $employee->presentUpazila->name ?? 'Has no upazilla'}},
                                    জেলাঃ {{ $employee->presentDistrict->name ?? 'Has no district'}} </p> --}}
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="present_address">
                    <table class="d-block">
                        <tr class="d-block">
                            <td><b>স্থায়ী ঠিকানাঃ</b></td>
                            <td style="width: 90%;">
                                <p>গ্রামঃ {{ $employee->permanent_village }},
                                    পোষ্টঃ {{ $employee->permanentUnion?->name }},
                                    উপজেলাঃ {{ $employee->permanentUpazila?->name }},
                                    জেলাঃ {{ $employee->permanentDistrict?->name }}</p>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="letter_body">
                    <p class="bisoy " style="font-size: 10px;"><b>বিষয়ঃ </b>চাকুরীর নিয়োগপত্র ।</p>
                    <p style="font-size: 10px;" class="">জনাব/জনাবা,</p>
                    <p class="b-font">গত <span class="relative">{{ date('d-m-Y', strtotime($employee->joining_date)) }}<span class="absolute d-content-span"></span></span> ইং তারিখে আবেদনপত্র এবং পরবর্তীকালে স্বাক্ষাতের পরিপ্রেক্ষিতে আপনাকে জানানো যাচ্ছে যে, আগামী <span class="relative">{{ date('d-m-Y', strtotime($employee->joining_date)) }}<span class="absolute d-content-span"></span></span> ইং তারিখ হতে নিম্ন শর্ত স্বাপেক্ষে আপনাকে অত্র প্রতিষ্ঠানে নিয়োগ দেওয়া হলোঃ
                    </p>
                    {{-- <p style="font-size: 10px;"><b>১। </b>গ্রেডঃ <span class="relative">{{ $employee->grade->name ?? 'Has no grade' }} <span class="absolute d-content-span"></span></span> এর অধীনে আপনার বর্তমান পদবীঃ <span class="relative"> {{ $employee->designation->name }}, <span class="absolute d-content-span"></span></span> সেকশনঃ <span class="relative">{{ $employee->section->name }}, <span class="absolute d-content-span"></span></span> আইডি নাম্বারঃ <span class="relative">{{ $employee->employee_id }}<span class="absolute d-content-span"></span></span></p> --}}
                    <p style="font-size: 10px;"><b>২। </b>আপনার বর্তমান বেতন নিম্নরুপঃ</p>
                    <table class="salary" style="font-size: 11px; border: 1px solid;">
                        <tr class="text-center">
                            <td style="width: 112px;">
                                মূল বেতন<br>
                                Basic
                            </td>
                            <td>
                                বাড়ী ভাড়া ( মূল বেতন এর ৫০ ভাগ ) <br>
                                House Rent
                            </td>
                            <td>
                                চিকিৎসা ( মূল বেতন এর ১০ ভাগ ) <br>
                                Medical Allowance
                            </td>
                            <td>
                                যাতায়াত ( মূল বেতন এর ৫ ভাগ )<br>
                                Transport Allowance
                            </td>
                            <td>
                                খাদ্য ভাতা ( মূল বেতন এর ২ ভাগ )<br>
                                Food Allowance
                            </td>
                            <td>
                                মোট বেতন <br>
                                Total Gross
                            </td>
                            <td>
                                ওভারটাইম রেট <br>
                                OT Rate
                            </td>
                        </tr>
                        <tr>
                            @php
                            $gross=$employee->salary;

                            $basic=round(($gross-1850)/1.5);
                            $house=intval($basic*50/100);
                            $medicale=intval($basic*10/100);
                            $transport=intval($basic*5/100);
                            $food=intval($basic*2/100);


                            @endphp

                            <td>{{ intval($basic) }}</td>
                            <td>{{ intval($house) }}</td>
                            <td>{{ intval($medicale) }}</td>
                            <td>{{ intval($transport) }}</td>
                            <td>{{ intval($food) }}</td>
                            {{-- <td>{{ $gross }}</td> --}}
                            {{-- <td>{{ number_format((float)$basic/208*2, 2, '.', '') }}</td> --}}
                            {{-- <td>{{ number_format((float)$basic+$house+$medicale+$transport+$food, 2, '.', '') }}</td> --}}
                        </tr>
                        <tr>
                            <td colspan="7" style="text-align: left;" class="b-font">মোট মঞ্জুরী ( কথায় ) :
                                <?php
                                    try
                                        {
                                        echo  convert_number($gross);
                                        }
                                    catch(Exception $e)
                                        {
                                        echo $e->getMessage();
                                        }
                                ?>
                            </td>
                        </tr>
                    </table>

                    <p style="font-size: 10px;"><b>৩। </b>আপনার চাকুরীর প্রথম ৩(তিন) মাস শিক্ষানবিস থাকবেন। শিক্ষানবিস থাকাকালীন যদি আপনার কাজের মান সন্তোষ-জনক না হয় তবে কতৃপক্ষ কোন প্রকার নোটিশ এবং কারন
                        দর্শানো ব্যাতিরেকে আপনাকে চাকরী হতে অপসারন করতে পারবেন। এই সময় আপনি কোন আবাসন ভাতা পাবেন না।</p>
                    <p style="font-size: 10px;"><b>৪। </b>প্রতি মাসের মজুরি পরবর্তী মাসের ৭ কর্মদিবসের মধ্যে পরিশোধ করা হবে।</p>
                    <p style="font-size: 10px;"><b>৫। </b>স্বাভাবিক কর্মসময় দিনে ৮ ঘন্টা। কতৃপক্ষ প্রয়োজনবোধে আপনাকে অতিরিক্ত সময় কাজ করাতে পারবে। সেক্ষেত্রে অতিরিক্ত সময়ের জন্য মূল মজুরির দ্বিগুন হারে পরিশোধ করা হবে। যার হিসাব নিম্নরুপঃ (মূল মজুরি /২০৮) x ২ x মোট অতিরিক্ত কর্মঘন্টা। </p>
                    <p style="font-size: 10px;"><b>৬। ফুরন ভিত্তিক (পিচ রেট) মজুরির হিসাব হইবে নিম্নরুপঃ</b></p>

                    <p style="font-size: 10px;"><span class="tab"></span>(ক) কারখানার ফ্লোর ইনচার্জের মাধ্যমে শ্রমিকদের নিকট হইতে প্রাথমিক ধারনা নিয়ে উর্ধতন কতৃপক্ষ বা মালিক ফুরন ভিত্তিক (পিচ রেট) মজুরী নির্ধারন করিবেন ।</p>
                    <p style="font-size: 10px;"><span class="tab"></span>(খ) কোন প্রকৃতি (style) এর ফুরন ভিত্তিক (পিচ রেট) মজুরী হার সম্পর্কে কোন পর্যায় হইতে কোন আপত্তি উত্থাপিত হইলে উহা কতৃপক্ষ কর্তৃক পূনর্বিবেচনার সুযোগ থাকবে।</p>
                    <p style="font-size: 10px;"><span class="tab"></span>(গ) কোন শ্রমিক তার নির্ধারিত গ্রেডের প্রাপ্য মজুরী অপেক্ষা কম পাইলে কারখানা কর্তৃপক্ষ উহা পূরন করিবেন।</p>

                    <p style="font-size: 10px;"><b>৭। </b>অতিরিক্ত কাজের মজুরী বেতনের সহিত প্রদান করা হইবে।</p>
                    <p style="font-size: 10px;"><b>৮। ছুটি / অবকাশঃ</b>প্রত্যেক শ্রমিক নিম্নোক্ত হারে ছুটি ভোগ করিবেনঃ </p>
                    <p style="font-size: 10px;">
                        <span class="tab"></span><span><b>(ক) সাপ্তাহিক ছুটিঃ</b> </span>
                        <span> প্রতি সপ্তাহে ০১ দিন বাংলাদেশ শ্রম-আইন অনুসারে সাপ্তাহিক ছুটি হিসাবে বিবেচিত হবে।</span>
                    </p>
                    <p style="font-size: 10px;">
                        <span class="tab"></span><span><b>(খ) উৎসব-জনিত ছুটিঃ</b> </span>
                        <span>বছরে ১১(এগার) দিন, পূর্ন বেতনে।</span>
                    </p>
                    <p style="font-size: 10px;">
                        <span class="tab"></span><span><b>(গ) নৈমত্তিক ছুটিঃ</b> </span>
                        <span> বছরে ১০(দশ) দিন পূর্ন বেতনে।</span>
                    </p>
                    <p style="font-size: 10px;">
                        <span class="tab"></span><span><b>(ঘ) অসুস্থতাজনিত ছুটিঃ</b> </span>
                        <span> বছরে ১৪(চৌদ্দ) দিন, পূর্ন বেতনে।</span>
                    </p>
                    <p style="font-size: 10px;">
                        <span class="tab"></span><span><b>(চ) অর্জিত জনিত ছুটিঃ</b></span>
                        <span>প্রতি আঠারো(১৮) কর্মদিবসে ১(এক) দিন, পূর্ন বেতনে। একটানা কমপক্ষে এক বছর চাকুরী পূর্ন করিলে এই ছুটি ভোগ করিতে পারবেন।</span>
                    </p>
                    <p style="font-size: 10px;">
                        <span class="tab"></span><span><b>(ছ) মাতৃকালীন ছুটিঃ</b></span>
                        <span> (নারী শ্রমিকের জন্য) স্ব-বেতনে ১৬(ষোল) সপ্তাহ বা ১১২(একশত বারো) দিন সন্তান (প্রসবের পূর্বে ৮ সপ্তাহ এবং পরে ৮ সপ্তাহ)
                            বাংলাদেশ শ্রম-আইন ৪র্থ অধ্যায়ের ৪৫ থেকে ৫৫ ধারা অনুসারে মাতৃকালীন ছুটি দেওয়া হবে।
                        </span>
                    </p>

                    <p style="font-size: 10px;"><b>৯। হাজিরা রেকর্ড ও পরিচয় পত্রঃ </b> হাজিরা নিশ্চিত করার জন্য আইডি কার্ড পাঞ্চ এবং বাহির রেকর্ড কম্পিউটার সিস্টেমে
                        সংরক্ষিত থাকবে। আপনাকে পরিচয় পত্রসহ একটি কর্ড দেওয়া হবে। পরিচয়পত্র কারখানায় প্রবেশ, কাজ, অবস্থান এবং প্রস্থানের সময় সাথে
                        রাখতে হবে এবং দেখাতে হবে।</p>
                    <p style="font-size: 10px;"><b>১০। চাকুরী হতে পদত্যাগ বা ইস্তফাঃ </b> প্রত্যেক শ্রমিক দুই মাসের লিখিত নোটিশ
                        দিয়ে পদত্যাগ করতে পারে, অন্যথায় ৬০(ষাট) দিনের মজুরি কোম্পানিকে প্রদান করে পদত্যাগ করা যাবে। অন্যদিকে মালিক কর্তৃক আপনার চাকুরি অবসান
                        ঘটলে ১২০(একশন বিশ) দিনের নোটিশ বা সমপরিমান মোট মজুরী প্রদান করবেন।</p>
                    <p style="font-size: 10px;"><b>১১। </b> আপনি কোম্পানির অন্যান্য সুযোগ-সুবিধা (যেমনঃ হাজিরা বোনাস ৫০০ টাকা এবং উৎসব বোনাস)
                        কোম্পানির প্রচলিত নিয়ম অনুযায়ী পাবেন।</p>
                    <p style="font-size: 10px;"><b>১২। </b> চাকুরীর মেয়াদ এক বছর পূর্ন হলে বেতনের ৫% বৃদ্ধি করা হবে।</p>
                    <p style="font-size: 10px;"><b>১৩। </b> সর্বপরি আপনার চাকুরীর অন্যান্য শর্তগুলি বাংলাদেশ শ্রম-আইন আনুসারে পরিচালিত হবে।</p>
                    <p style="font-size: 10px;" class="my-2 text-center">উপরের বর্ণিত শর্তাদি যদি আপনার গ্রহনযোগ্য মনে হয় তবে এই নিয়োগপত্রে স্বাক্ষর করে নিম্ন স্বাক্ষরকারীর নিকট ফেরন দিন।</p>

                </div>

                <div class="letter_sign">
                    <table width=100%>
                        <tr class="d-block">
                            <td style="font-size: 10px; width: 33%">
                                <p>নিয়োগকর্তার স্বাক্ষরঃ _______________</p><br>
                                <p>তারিখঃ ________________________</p>
                            </td>
                            <td style=" width: 33%"></td>
                            <td style="font-size: 10px; width: 33%; float: right; margin-top: -40px;">
                                <p>স্বাক্ষরঃ _________________</p><br>
                                <p>পূর্ন নামঃ ________________</p>
                            </td>
                        </tr>

                    </table>
                </div>
            </div>
        </div>
        @endforeach
