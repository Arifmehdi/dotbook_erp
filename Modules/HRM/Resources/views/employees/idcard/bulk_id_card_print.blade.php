{{-- ID Card by Selected Employees --}}

<link rel="stylesheet" href="{{ asset('css/idcard/bulk.css') }}">

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

    .card-body {
        min-width: 100%;
        min-height:100%;
    }

</style>

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

    $id_card_setting = $settings->id_card_setting;
    $isInEnglish = true;
@endphp

<style type="text/css" media="print">
    @media print {
        @page {
            size: A4 landscape;
            max-width: 100%;
            max-height: 100%
        }

        .vertical {
            width: 100%;
            -webkit-transform: rotate() scale(.80, .68);
        }

    }

    .cmName p {
        font-size: 8px;
        background: #ff9a42;
        padding: 0 1px;
        font-weight: 600;
    }

    .card-row >*:nth-child(4n-3), .card-row >*:nth-child(4n-2) {
        margin-top: 0 !important;
    }

</style>
<div class="row g-2 mt-0 card-row">


@foreach($employees as $key=> $user)
    <div class="col-6">
        <div class="pagebreak">
            <div class="content">
                <div class="row g-1">
                    <div class="col-6" id="printarea">
                        <div class="main-card" >
                            <div class="card-body p-1 m-0 border-primary">
                                <div class="card-head">
                                    <div class="m-0 p-0 cmName">
                                        <h2 style="font-size:14px;">
                                            <img src="{{ asset('modules/hrm/logo.png') }}"  style="height: 20px; margin-right:8px" class="logo">
                                            {{ json_decode($settings->business)->shop_name }}
                                        </h2>

                                        <p style="font-size: 10px; margin:0;padding:0;font-weight:400;line-height:10px;">Manufacture & Exporter of Quality Garments</p>

                                    </div>
                                </div>
                                <div class="cardContent">
                                    <div class="userImgMain">
                                        @if(File::exists('uploads/employees/'.$user->photo))

                                        <img src="{{ asset( 'uploads/employees/'.$user->photo) }}" class="userImg" style="height:75px; width:75px;">
                                        @else
                                        <img src="{{ asset("modules/hrm/nophoto.png") }}" class="userImg" style="height:75px; width:75px;">
                                        @endif
                                    </div>

                                    <div class="userInfo">
                                        <table>
                                            <tr >
                                                <td > {{ __('ID No.') }} </td>
                                                <td > {{ $user->employee_id }} </td>
                                            </tr>
                                            <tr>
                                                <td >{{ __('Name') }} </td>
                                                @php
                                                    $trimedName = '';
                                                    $nameArr = \preg_split('/\s+/', $user->name);
                                                    $trimedName = $nameArr[0];

                                                    if(isset($nameArr[1])) {
                                                        $trimedName .= ' '. $nameArr[1];
                                                    }
                                                @endphp
                                                <td > {{  Str::limit($trimedName, 17, '') }} </td>
                                            </tr>
                                            <tr>
                                                <td >{{ __('Designation') }}</td>
                                                <td >{{ $user->position_name }} </td>
                                            </tr>
                                            <tr>
                                                <td >{{ __('Section') }}</td>
                                                <td >{{ $user->division_name }} </td>
                                            </tr>
                                            <tr>
                                                <td >{{ __('Joining') }}</td>
                                                @php
                                                $joining_date=date('d/m/Y', strtotime($user->joining_date));
                                                $expire_date=date('d/m/Y', strtotime($user->termination_date));
                                                @endphp
                                                <td > {{ $joining_date }} </td>
                                            </tr>
                                            <tr>
                                                <td >{{ __('Expired') }}</td>
                                                <td >{{ $expire_date }} </td>
                                            </tr>
                                            <tr>
                                                <td >{{ __('Blood') }}</td>
                                                <td > {{ $user->blood }} </td>
                                            </tr>

                                        </table>
                                    </div>
                                </div>
                                <div class="id-card-footer">
                                    <div class="signature d-block">
                                        <div class="d-flex gap-3 align-items-end">
                                            <div class="w-50">
                                                <div class="holder">
                                                    <p>Holder Signature</p>
                                                </div>
                                            </div>
                                            <div class="w-50">
                                                <img src="{{ asset("modules/hrm/signature.png") }}"  style="height:40px; max-width:100%; margin-bottom:3px">
                                                <div class="author text-end">
                                                    <p>Authorised By</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ===============================BACK SIDE START================= -->
                    <div class="col-6">
                        <div class="card-body p-1 m-0 border-primary">
                            <div class="id-bg">

                                <p class="request">If Found please return to</p>
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
                                    <p class="sec-title">@if($user->name == NULL) Permanent Address: @else স্থায়ী ঠিকানা : @endif</p>
                                    <p> @if($user->name == NULL)
                                        {{ $user->village }} , {{ $user->upazila }} , {{ $user->union }} , {{ $user->district }}
                                        @else {{ $user->permanent_address }}
                                        @endif
                                    </p>
                                    <p>@if($user->name == NULL)NID @else জাতীয় পরিচয়পত্র: @endif @if($user->name == NULL) {{ $user->nid }} @else {{ BanglaConverter::en2bn($user->nid) }} @endif</p>
                                    <p>@if($user->name == NULL) Mobile: @else মোবাইল:@endif @if($user->name == NULL) {{ $user->phone }} @else{{ BanglaConverter::en2bn($user->phone) }}@endif</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
</div>
