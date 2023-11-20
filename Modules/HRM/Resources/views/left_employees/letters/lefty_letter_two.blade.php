@include('hrm::layouts.mpdf.header')

<style>
    table {
        padding-top: 20px;
    }

    td,
    td p {
        font-size: 18px;
        line-height: 18px;
    }

</style>

<div class="main">
    <div class="main_content_wrapper">

        @include('hrm::layouts.mpdf.address')

        <div class="letter_body" style="font-size: 18px; line-height:18px;">

            <p class="bisoy b-font" style="margin-top: 20px;"><b>বিষয়ঃ </b> বাংলাদেশ শ্রম-আইন ২০০৬ এর ২৭(৩ক) ধারা মোতাবেক আত্বপক্ষ সমর্থনের সুযোগ প্রদান প্রসঙ্গে ।</p>
            <p class="b-font" style="margin-top: 10px;">জনাব,</p>
            <p class="b-font" style="margin-top:5px;">আপনি গত<span class="relative"> ................................................. <span class="absolute d-content-span"></span></span>তারিখ থেকে কারখানা কতৃপক্ষ্যের বিনা অনুমতিতে কর্মস্থলে অনুপস্থিত রয়েছেন। এ প্রেক্ষিতে কারখানা
                কর্তৃপক্ষ আপনার স্থায়ী ও বর্তমান ঠিকানায় রেজিষ্ট্রি ডাকযোগাযোগে গত <span class="relative">.................................................<span class="absolute d-content-span"></span></span>তারিখে বিনা অনুমতিতে চাকুরীতে অনুপস্থিতর কারন ব্যাখ্যা সহ
                কাজে যোগদানের জন্য পত্র প্রেরন করেছে। কিন্তু অদ্যবধি আপনি উপরোক্ত বিষয়ে কোন ধরনের লিখিত ব্যাখ্যা প্রদান করেন নাই ।
            </p>
            <p class="b-font" style="margin-top: 20px;">সুতরাং অত্র পত্র পাপ্তির ১০(দশ) দিনের মধ্যে আত্বপক্ষ সমর্থন সহ কাজে যোগদান করিতে আপনাকে নির্দেশ দেওয়া গেল ।</p>
            <p class="b-font" style="margin-top: 20px;">উক্ত সময়ের মধ্যে আপনি আত্বপক্ষ সমর্থনের জবাব সহ কাজে যোগদান করতে ব্যার্থ হলে বাংলাদেশ শ্রম-আইন ২০০৬ এর
                ২৭(৩ক) ধারা অনুযায়ী আপনি স্বেচ্ছায় চাকুরি থেকে অব্যাহতি গ্রহন করেছেন বলে গন্য হবে।
            </p>
        </div>

        <div class="letter_sign" style="margin-top: 60px;">
            @include('hrm::layouts.mpdf.sign')
        </div>
    </div>
</div>

@include('hrm::layouts.mpdf.footer')
