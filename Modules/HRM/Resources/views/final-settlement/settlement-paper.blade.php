<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Final Settlement</title>
    @include('layout.mpdf.header')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        table, tr, td, td {
            border: 1px solid black;
            font-size: 11px;
        }
        td, td {
            padding: 3px;
        }
        .b1 {
            border: 1px solid black;
        }
        .text-center {
            text-align: center;
        }
        .mp0 {
            padding: 0;
            margin: 0;
        }
        .b1 {
            border: 1px solid black;
        }
        .flex-center {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 60vh;
            border: 1px solid black;
        }
        .py-5 {
            padding-top: 10px;
            padding-bottom: 10px;
        }
        .pb-5 {
            padding-bottom: 10px;
        }
        .pt-5 {
            padding-top: 10px;
        }
        .mb-5 {
            margin-bottom: 10px;
        }
        .mt-5 {
            margin-top: 10px;
        }
        .text-small {
            font-size: 10px;
        }
        .float-start, .float-left {
            float: left;
        }

        .float-end, .float-right {
            float: right;
        }
        .d-100 {
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="py-5">
        <h4 class="text-center mp0">{{ config('app.name') }}</h4>
        <p class="text-center mp0 text-small">
            {{ json_decode($settings->business)->address }}
        </p>
    </div>

    <div class="pt-5">
        <h3 class="text-center mp0">চুড়ান্ত নিস্পত্তিকরণ </h3>
        <p class="text-center mp0 text-small">(বাংলাদেশ শ্রম আইন ২০০৬ মোতাবেক)</p>
    </div>

    <div class="mt-2 pt-2 ps-1">
        <p class="float-start w-50 text-start text-left">ধরনঃ পদত্যাগ</p>
        <p class="float-start w-50 text-rigt text-end">তারিখঃ ...../...../২০..... &nbsp;&nbsp;ইং</p>
    </div>
    {{-- 1 --}}
    <table class="w-100">
        <tr class="w-100 b1">
            <td class="w-50 b1">নামঃ {{ $employee->employee_nme }}</td>
            <td class="w-50 b1">কার্ড/ আই.ডি নং: {{ $employee->employee_id }}</td>
        </tr>
        <tr class="w-100 b1">
            <td class="b1">পদবীঃ {{ $employee->designation_name }}</td>
            <td class="b1">সেকশনঃ {{ $employee->subsection_name }}</td>
        </tr>
        <tr class="w-100 b1">
            <td class="b1">যোগদানের তারিখঃ</td>
            <td class="b1">{{ $employee->joining_date }} ইং</td>
        </tr>
        <tr class="w-100 b1">
            <td class="b1">ইস্তফাপত্র জমার তারিখঃ</td>
            <td class="b1">{{ $employee->submission_date }} ইং</td>
        </tr>
        <tr class="w-100 b1">
            <td class="b1">ইস্তফাপত্র কার্যকরীর তারিখঃ</td>
            <td class="b1">{{ $employee->approval_date }} ইং</td>
        </tr>
        <tr class="w-100 b1">
            <td class="b1"  rowspan="3">
                চাকুরীকালঃ
                <br>
                <br>
                {{ $employee->joining_date }} হইতে {{ $employee->approval_date }} পর্যন্ত
            </td>
            <td class="b1">{{ $employee->serviceYear }} বৎসর</td>
        </tr>
        <tr class="w-100 b1">
            <td class="b1">{{ $employee->serviceMonth }} মাস</td>
        </tr>
        <tr class="w-100 b1">
            <td class="b1">{{ $employee->serviceDay }} দিন</td>
        </tr>
    </table>
    <br>
    <br>

    {{-- 2 --}}
    <table class="w-75">
        <tr class="w-100 b1">
            <td class="w-50 b1 text-center py-2" colspan="2">মজুরী সংক্রান্ত তথ্য (টাকায়)</td>
        </tr>
        <tr class="w-100 b1">
            <td class="b1">মূল মজুরী</td>
            <td class="b1">{{ $employee->basic }}</td>
        </tr>
        <tr class="w-100 b1">
            <td class="b1">বাড়ী ভাড়া ভাতা </td>
            <td class="b1">{{ $employee->house_rent }}</td>
        </tr>
        <tr class="w-100 b1">
            <td class="b1">চিকিৎসা ভাতা </td>
            <td class="b1">{{ $employee->medical }}</td>
        </tr>
        <tr class="w-100 b1">
            <td class="b1">যাতায়াত ভাতা </td>
            <td class="b1">{{ $employee->transport }}</td>
        </tr>
        <tr class="w-100 b1">
            <td class="b1">খাদ্য ভাতা  </td>
            <td class="b1">{{ $employee->food }}</td>
        </tr>
        <tr class="w-100 b1">
            <td class="b1 text-center1">সর্ব মোট মজুরী  =  </td>
            <td class="b1">{{ $employee->sub_total }}</td>
        </tr>
        <tr class="w-100 b1 py-2">
            <td class="b1"><b>দৈনিক মজুরী </b></td>
            <td class="b1">{{ $employee->daily_rate }}</td>
        </tr>

    </table>

    <br>
    <br>
    {{-- 3 --}}

    <table class="w-100">
        <tr class="w-100 b1">
            <td class="w-25 b1">অর্জিত ছুটি বাবদ </td>
            <td class="w-25 b1">
                @php $elReportCount = count($el_full_report['el_report']); $payable_days = 0; $payable_money = 0; @endphp
                @foreach($el_full_report['el_report'] as $report)
                    {{ $report['payable_el'] }} ({{ $report['year'] }})
                    @if( $elReportCount >= 1)
                        @if($loop->last)
                            =
                        @else
                            +
                        @endif
                    @endif
                @endforeach
                {{  $el_full_report['total_payable_days'] }}
                দিন
            </td>
            <td class="w-25 b1">
                {{  $el_full_report['total_payable_money'] }}
            </td>
        </tr>
        <tr class="w-100 b1">
            <td class="w-25 b1">সার্ভিস বেনিফিট  </td>
            <td class="w-25 b1"></td>
            <td class="w-25 b1">{{ $employee->serviceBenefit }}</td>
        </tr>
        <tr class="w-100 b1">
            <td class="w-25 b1">মোট</td>
            <td class="w-25 b1"></td>
            <td class="w-25 b1">{{ $el_full_report['money_sub_total'] }}</td>
        </tr>

        <tr class="w-100 b1">
            <td class="w-25 b1">স্ট্যাম্প বাবদ কর্তন</td>
            <td class="w-25 b1"></td>
            <td class="w-25 b1">{{ $el_full_report['stamp'] }}</td>
        </tr>

        <tr class="w-100 b1">
            <td class="w-25 b1">অগ্রীম</td>
            <td class="w-25 b1"></td>
            <td class="w-25 b1">{{ $el_full_report['advanced'] }}</td>
        </tr>

        <tr class="w-100 b1">
            <td class="w-25 b1" colspan="2">সর্বসাকুল্যে পরিশোধ </td>
            <td class="w-25 b1">{{ $el_full_report['money_grand_total'] }}</td>
        </tr>

        <tr class="w-100 b1">
            <td class="w-25 b1" colspan="3">সর্বসাকুল্যে পরিশোধ (কথায়) :  {{ $el_full_report['money_grand_total_text'] }} </td>
        </tr>

    </table>
    <br>
    <p>** পাওনাদির সাথে সার্ভিস বই গ্রহন করিলাম। </p>

    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>

    <div class="d-float text-small">
        <div class="float-start w-25 text-center">গ্রহনকারীর সাক্ষর </div>
        <div class="float-start w-25 text-center">প্রস্তুতকারী  </div>
        <div class="float-start w-25 text-center">এইচ আর এন্ড কমপ্লায়েন্স   </div>
        <div class="float-start w-25 text-center">নির্বাহী পরিচালক    </div>
    </div>
</body>
</html>
