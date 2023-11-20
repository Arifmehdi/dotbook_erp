<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Table</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100;200;300;400;500;700;800;900&family=Tajawal:wght@500;700&display=swap" rel="stylesheet">
    <style>
         * {
            margin: 0px;
            padding: 0px;
            font-family: 'Tajawal', sans-serif;
        }

        h1 {
            text-align: center;
            font-size: 10px;
            margin-top: 1px;
        }

        p {
            text-align: center;
            font-family: verdana;
            line-height: 8px;
            font-size: 8px;
            font-weight: 500;
        }

        h2 {
            text-align: center;
            font-size: 8px;
            line-height: 10px;
        }

        .container {
            width: 50%;
            float: left;
            padding: 1px 13px;
            padding-top: 4px;
            margin-top: 0px;
            box-sizing: border-box;
        }

        .pay {
            word-spacing: 0px;
            font-family: Tajawal, sans-serif;
            position: relative;
            font-size: 9px;
        }

        .date {
            position: absolute;
            left: 123px;
            bottom: 2px;
            font-size: 8px;
        }

        .section-table {
            margin: 0px auto auto;
        }

        table,
        th,
        td {
            text-align: center;
            font-size: 8px;
            border: 1px solid #121212;
            border-collapse: collapse;
        }

        td {
            padding: 0px;
        }

        .signature {
            display: flex;
            height: 40px;
            font-size: 10px;
            justify-content: space-between;
        }

        .signature span {
            margin-top: 30px;
        }
        /* .for-one {
            height: 30% !important;
        } */

        .wrapper-page {
            page-break-after: always;
            break-inside: avoid;
        }

        .wrapper-page:last-child {
            page-break-after: avoid;
            break-inside: avoid;
        }

        @page {
            margin : 1cm 0.5cm;
            padding: 0;
        }

        .print_area {
            float: none;
        }
        .bb1 {
            border-bottom: 1px solid black;
        }
    </style>

</head>

<body>

    {{-- _convert number english to bangla --}}
    <?php
      class BanglaConverter {
          public static $bn = array("১", "২", "৩", "৪", "৫", "৬", "৭", "৮", "৯", "০");
          public static $en = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0");

          public static function bn2en($number) {
              return str_replace(self::$bn, self::$en, $number);
          }

          public static function en2bn($number) {
              return str_replace(self::$en, self::$bn, $number);
          }
      }
    ?>



  <div class="print_area">
    @foreach($employees as $row_key => $employee)

    {{-- <div class="for-one" style="border: 1px solid red;"> --}}
    <div class="wrapper-page">

       <div class="bb12">
        <div class="container">
            <h1>{{ json_decode($settings->business)->shop_name }}</h1>
            <p>Barpa, Rupgonj, Narayangonj, Bangladesh</p>
            <h2><u>অফিস কপি</u></h2>
            <div class="pay">Pay slip for the month of .................................
                <div class="date">{{ trim($month_name) }}-{{ trim($year) }}</div>
            </div>
            <div class="section-table">
                <table style="width: 100%">
                    <tr>
                        <th colspan="2">নামঃ {{ trim($employee->employee_name) }} </th>
                        <th>কার্ড নং</th>
                        <th>{{ BanglaConverter::en2bn(trim($employee->employee_id)) }}</th>
                        <th>গ্রেড</th>
                        <th>{{ trim($employee->grade_name) }}</th>
                    </tr>
                    <tr>
                        <td colspan="2">পদবীঃ {{ trim($employee->designation_name) }}</td>
                        <td>সেকশনঃ</td>
                        <td colspan="2">{{ trim($employee->section_name) }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>মোট বেতনঃ</td>
                        <td>{{ BanglaConverter::en2bn(trim($employee->salary)) }}</td>
                        <td>টাকা</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>মূল বেতনঃ</td>
                        <td>{{ BanglaConverter::en2bn(trim($employee->basic)) }}</td>
                        <td>টাকা</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>বাড়ি ভাড়া</td>
                        <td>{{ BanglaConverter::en2bn(trim($employee->house_rent)) }}</td>
                        <td>টাকা</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>খাদ্য + যাতায়াত + চিকিৎসা</td>
                        <td>{{ BanglaConverter::en2bn(trim($employee->food)) }}+{{ BanglaConverter::en2bn(trim($employee->transport)) }}+{{ BanglaConverter::en2bn(trim($employee->medical)) }}</td>
                        <td>টাকা</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>মোট কাজের মজুরিঃ</td>
                        <td></td>
                        <td>টাকা</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>প্রোডাকশন বোনাসঃ</td>
                        <td>0</td>
                        <td>টাকা</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>হাজিরা বোনাসঃ</td>
                        <td>{{ BanglaConverter::en2bn(trim($employee->attendance_bonus ?? '0' )) }} </td>
                        <td>টাকা</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>ছুটির টাকাঃ</td>
                        <td></td>
                        <td>টাকা</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>মোট প্রদেয়ঃ</td>
                        <td>{{ BanglaConverter::en2bn(trim($employee->gross_pay ?? '0')) }}</td>
                        <td>টাকা</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>ও.টি. রেটঃ</td>
                        <td>{{ BanglaConverter::en2bn(trim($employee->over_time_rate ?? '0' )) }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>ও.টি. ঘণ্টাঃ</td>
                        <td>{{ BanglaConverter::en2bn(trim($employee->over_time ?? '0' )) }}</td>
                        <td>টাকা</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>ও.টি. মূল্যঃ</td>
                        <td>{{ BanglaConverter::en2bn(trim($employee->over_time_amount ?? '0' )) }}</td>
                        <td>টাকা</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>প্রদেয়ঃ</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>অগ্রিমঃ</td>
                        <td>{{ BanglaConverter::en2bn(trim($employee->advance ?? '0')) }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>স্ট্যাম্পঃ</td>
                        <td>{{ BanglaConverter::en2bn(trim($employee->stamp ?? '0')) }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>সর্বমোট</td>
                        <td>{{ BanglaConverter::en2bn(trim($employee->payable_salary ?? '0')) }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </table>
            </div>
            <div class="signature">
                <span>কর্মীর স্বাক্ষর</span>
                <span>কর্তৃপক্ষের স্বাক্ষর</span>
            </div>
        </div>
        <div class="container">
            <h1>{{ json_decode($settings->business)->shop_name }}</h1>
            <p>Barpa, Rupgonj, Narayangonj, Bangladesh</p>
            <h2><u>কর্মী কপি</u></h2>
            <div class="pay">Pay slip for the month of .................................
                <div class="date">{{ trim($month_name) }}-{{ trim($year) }}</div>
            </div>
            <div class="section-table">
                <table style="width: 100%">
                    <tr>
                        <th colspan="2">নামঃ {{ trim($employee->employee_name) }} </th>
                        <th>কার্ড নং</th>
                        <th>{{ BanglaConverter::en2bn(trim($employee->employee_id)) }}</th>
                        <th>গ্রেড</th>
                        <th>{{ trim($employee->grade_name) }}</th>
                    </tr>
                    <tr>
                        <td colspan="2">পদবীঃ {{ trim($employee->designation_name) }}</td>
                        <td>সেকশনঃ</td>
                        <td colspan="2">{{ trim($employee->section_name) }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>মোট বেতনঃ</td>
                        <td>{{ BanglaConverter::en2bn(trim($employee->salary)) }}</td>
                        <td>টাকা</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>মূল বেতনঃ</td>
                        <td>{{ BanglaConverter::en2bn(trim($employee->basic)) }}</td>
                        <td>টাকা</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>বাড়ি ভাড়া</td>
                        <td>{{ BanglaConverter::en2bn(trim($employee->house_rent)) }}</td>
                        <td>টাকা</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>খাদ্য + যাতায়াত + চিকিৎসা</td>
                        <td>{{ BanglaConverter::en2bn(trim($employee->food)) }}+{{ BanglaConverter::en2bn(trim($employee->transport)) }}+{{ BanglaConverter::en2bn(trim($employee->medical)) }}</td>
                        <td>টাকা</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>মোট কাজের মজুরিঃ</td>
                        <td></td>
                        <td>টাকা</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>প্রোডাকশন বোনাসঃ</td>
                        <td>0</td>
                        <td>টাকা</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>হাজিরা বোনাসঃ</td>
                        <td>{{ BanglaConverter::en2bn(trim($employee->attendance_bonus ?? '0' )) }} </td>
                        <td>টাকা</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>ছুটির টাকাঃ</td>
                        <td></td>
                        <td>টাকা</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>মোট প্রদেয়ঃ</td>
                        <td>{{ BanglaConverter::en2bn(trim($employee->gross_pay ?? '0')) }}</td>
                        <td>টাকা</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>ও.টি. রেটঃ</td>
                        <td>{{ BanglaConverter::en2bn(trim($employee->over_time_rate ?? '0' )) }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>ও.টি. ঘণ্টাঃ</td>
                        <td>{{ BanglaConverter::en2bn(trim($employee->over_time ?? '0' )) }}</td>
                        <td>টাকা</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>ও.টি. মূল্যঃ</td>
                        <td>{{ BanglaConverter::en2bn(trim($employee->over_time_amount ?? '0' )) }}</td>
                        <td>টাকা</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>প্রদেয়ঃ</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>অগ্রিমঃ</td>
                        <td>{{ BanglaConverter::en2bn(trim($employee->advance ?? '0')) }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>স্ট্যাম্পঃ</td>
                        <td>{{ BanglaConverter::en2bn(trim($employee->stamp ?? '0')) }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>সর্বমোট</td>
                        <td>{{ BanglaConverter::en2bn(trim($employee->payable_salary ?? '0')) }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </table>
            </div>
            <div class="signature">
                <span>কর্মীর স্বাক্ষর</span>
                <span>কর্তৃপক্ষের স্বাক্ষর</span>
            </div>
        </div>
       </div>

    </div>

    <div class="display" id="break_page" style='page-break-after: always;'></div>

    @endforeach
  </div>
</body>


</body>

</html>
