<div class="line_one">
    <table class="d-block" style="margin-bottom: 20px;">
        <tr>
            <td style="width: 30%;">
                <p class="">তারিখঃ.................................................</p>
            </td>
            <td style="width: 30%;"></td>
            <td style="width: 30%;" style="text-align: right;">
                <p style="">"রেজিষ্ট্রি ডাক যোগে প্রেরিত"</p>
            </td>
        </tr>
    </table>
    <table class="d-block">
        <tr>
            <td style="width: 30%;">
                নামঃ {{ $user->name }}
            </td>
            <td style="width: 30%;">
                কার্ড নাম্বারঃ {{ $user->employee_id }}
            </td>
            <td style="width: 30%;">
                পিতার নামঃ {{ $user->father_name }}
            </td>
        </tr>
    </table>

    <table class="d-block" style="margin-top: 30px;">
        <tr>
            <td style="width:30%;">বর্তমান ঠিকানা</td>
            <td style="width:30%;">গ্রাম/মহল্লাঃ {{ $user->present_address }}</td>
            <td style="width: 30%;">
                <p>ডাকঘরঃ {{ $user->present_post_office }}</p>
            </td>
        </tr>
        <tr>
            <td style="width:30%;"></td>
            <td style="width:30%;">উপজেলাঃ {{ $user->get_present_thana?->name }}</td>
            <td style="width: 30%;">
                <p>জেলাঃ {{  $user->get_present_district?->name }}</p>
            </td>
        </tr>
    </table>
    <table class="d-block" style="margin-top: 30px;">
        <tr>
            <td style="width:30%;">স্থায়ী ঠিকানা</td>
            <td style="width:30%;">গ্রাম/মহল্লাঃ {{  $user->village }}</td>
            <td style="width: 30%;">
                <p>ডাকঘরঃ  {{  $user->postoffice?->name }}</p>
            </td>
        </tr>
        <tr>
            <td style="width:30%;"></td>
            <td style="width:30%;">উপজেলাঃ {{ $user->thana?->name }}</td>
            <td style="width: 30%;">
                <p>জেলাঃ {{ $user->district?->name }}</p>
            </td>
        </tr>
    </table>
</div>
