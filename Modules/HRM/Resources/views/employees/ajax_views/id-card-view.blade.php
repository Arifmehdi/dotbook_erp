<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Employee ID  Card</title>
    <link rel="stylesheet" href=" {{ asset('modules/hrm/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/idcard/bulk.css') }}">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script type="text/JavaScript" src="https://cdnjs.cloudflare.com/ajax/libs/jQuery.print/1.6.0/jQuery.print.js"></script>
    <style>
        .id-card-footer {
            padding-top: 0;
        }

        .signature {
            padding-top: 0;
        }

        td {
            line-height: 1.5;
        }

        .id-bg p {
            font-size: 11px;
        }

        .detels {
            padding: 3px !important;
        }

        .groupName {
            margin-bottom: 3px;
        }

        .id-card-footer p {
            margin-bottom: 0 !important;
        }
        .btn {
            display: block;
            height: 30px;
            line-height: 28px;
            padding: 0 20px;
            cursor: pointer;
            margin: auto;
            text-transform: capitalize;
            background: #fff;
            border: 1px solid #c3c3c3;
            border-radius: 5px;
            transition: .3s;
        }
        .btn:hover {
            background: #f7f7f7;
        }
    </style>
</head>

<body style="position:fixed; width:100%; height:100%; background:#e9e9e9;">
    {{-- english number to bangla number convert --}}
    @php
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
    @endphp
    <br>
    <div class="content">
        <div class="row g-2">
            <div class="col-6">
                <div class="pagebreak">
                    <div class="content" id="printarea">
                        <div style="display:flex; justify-content:center; gap:10px; background:#fff; padding:30px; border-radius:5px; width:max-content; margin:auto">
                            <div>
                                <div class="card-body" style="margin:0">
                                    <div class="card-head">
                                        <div class="m-0 p-0 cmName">
                                            <h2 style="font-size:14px;">
                                                <img src="{{ asset('modules/hrm/logo.png') }}" style="height: 20px; margin-right:8px" class="logo">
                                                {{ json_decode($generalSettings->business)->shop_name }}
                                            </h2>

                                            <p style="font-size: 10px; margin:0;padding:0;font-weight:400;line-height:10px;">Manufacture & Exporter of Quality Garments</p>

                                        </div>
                                    </div>
                                    <div class="cardContent">
                                        <div class="userImgMain">
                                            @if(File::exists($employee->photo))
                                            <img src="{{ asset($employee->photo) }}" class="userImg"  style="height:75px; width:75px;">
                                            @else
                                            <img src="{{ asset("modules/hrm/nophoto.png") }}" class="userImg"  style="height:75px; width:75px;">
                                            @endif
                                        </div>


                                        <div class="userInfo">
                                            <table>
                                                <tr >
                                                    <td > {{ __('ID No.') }} </td>
                                                    <td > {{ $employee->employee_id }} </td>
                                                </tr>
                                                <tr>
                                                    <td >{{ __('Name') }} </td>
                                                    <td > {{ $employee->name }} </td>
                                                </tr>
                                                <tr>
                                                    <td >{{ __('Designation') }}</td>
                                                    <td >{{ $employee->position_name }} </td>
                                                </tr>
                                                <tr>
                                                    <td >{{ __('Section') }}</td>
                                                    <td >{{ $employee->division_name }} </td>
                                                </tr>
                                                <tr>
                                                    <td >{{ __('Joining') }}</td>
                                                    @php
                                                    $joining_date=date('d/m/Y', strtotime($employee->joining_date));
                                                    $expire_date=date('d/m/Y', strtotime($employee->termination_date));
                                                    @endphp
                                                    <td > {{ $joining_date }} </td>
                                                </tr>
                                                <tr>
                                                    <td >{{ __('Expired') }}</td>
                                                    <td >{{ $expire_date }} </td>
                                                </tr>
                                                <tr>
                                                    <td >{{ __('Blood') }}</td>
                                                    <td > {{ $employee->blood }} </td>
                                                </tr>

                                            </table>
                                        </div>
                                    </div>
                                    <div class="id-card-footer">
                                        <div class="signature" style="display: block">
                                            <div style="display:flex; gap:30px; align-items:flex-end">
                                                <div style="width:50%">
                                                    <div class="holder">
                                                        <p>Holder Signature</p>
                                                    </div>
                                                </div>
                                                <div style="width:50%;text-align:right">
                                                    <img src="{{ asset("modules/hrm/signature.png") }}"  style="height:40px; max-width:100%; margin-bottom:3px">
                                                    <div class="author">
                                                        <p>Authorised By</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- ===============================BACK SIDE START================= -->
                            <div>

                                <div class="card-body" style="height: calc(100% - 12px); margin:0">
                                    <div class="id-bg">
                                        <p class="request">If Found please return to</p>
                                        <img src="img/logo.png" class="logo" alt="">
                                        <h2 class="cm-name">
                                             {{-- {{ $settings->general_settings['company_name'] }} --}}
                                        </h2>
                                        <h4 class="groupName">Majumder Group</h4>

                                        <div class="detels">
                                            <p class="sec-title">
                                                Head Office:
                                            </p>
                                            <p>Resourceful Paltan City, (11th floor) 51/51A Purana Paltan, Dhaka-1000</p>
                                        </div>
                                        <div class="detels">
                                            <p class="sec-title">
                                                Factory:
                                            </p>
                                            <p>Barpa, Rupshi ,Rupgonj, Narayangonj, Bangladesh</p>
                                            <p>Phone Number: +8801847429844 +8801723226868</p>
                                        </div>

                                        <div class="detels">
                                            <p class="sec-title">@if($employee->name == NULL) Permanent Address: @else স্থায়ী ঠিকানা : @endif</p>
                                            <p> @if($employee->name == NULL)
                                                 {{ $employee->village }} , {{ $employee->upazila }} , {{ $employee->union }} , {{ $employee->district }}
                                                @else {{ $employee->permanent_address }}
                                               @endif
                                            </p>
                                            <p>@if($employee->name == NULL)NID @else জাতীয় পরিচয়পত্র: @endif  @if($employee->name == NULL) {{ $employee->nid }} @else {{ BanglaConverter::en2bn($employee->nid) }} @endif</p>
                                            <p>@if($employee->name == NULL) Mobile: @else মোবাইল:@endif @if($employee->name == NULL) {{ $employee->phone }} @else{{ BanglaConverter::en2bn($employee->phone) }}@endif</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <button href="#" class="btn btn-sm btn-info" id="print">  print now </button>
    </div>

<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
	$("#print").click(function () {
         var id = {{ $employee->id }};
         $.ajax({
            url: "{{ route('hrm.employee.id.card.print_count') }}",
            type: "POST",
            data : { id: id}
         });
         $("#printarea").print();
   });

//    $("#print").click(function () {

//    });
</script>
</body>

</html>
