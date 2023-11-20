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

            <p class="bisoy b-font" style="margin-top: 20px;"><b>বিষয়ঃ </b> বাংলাদেশ শ্রম-আইন ২০০৬ এর ২৭(৩ক) ধারা মোতাবেক শ্রমিক কর্তৃক স্বেচ্ছায় চাকুরী হতে অব্যাহতি প্রসঙ্গে ।</p>
            <p class="b-font" style="margin-top: 10px;">জনাব,</p>
            <p class="b-font" style="margin-top: 5px;">আপনি গত<span class="relative"> ................................................. <span class="absolute d-content-span"></span></span>তারিখ হতে অদ্যবধি পর্যন্ত কর্তৃপক্ষের বিনা অনুমতিতে
                কর্মস্থলে অনুপস্থিত থাকার কারনে আপনাকে গত <span class="relative"> ................................................. <span class="absolute d-content-span"></span></span>তারিখে পত্রের মাধ্যমে ..... দিনের
                সময় দিয়ে চাকুরীতে যোগদান সহ ব্যাখ্যা প্রদান করতে বলা হয়েছিল। কিন্তু আপনি নির্ধারিত সময়ের মধ্যে কর্মস্থলে উপস্থিত হননি এবং কোন ব্যাখ্যা প্রদান করেননি।
                তথাপি কর্তৃপক্ষ গত <span class="relative"> ................................................. <span class="absolute d-content-span"></span></span> ইং তারিখে আর একটি পত্র প্রদানের মাধ্যমে আপনাকে আরো
                ৭(সাত) দিনের সময় দিয়ে আত্বপক্ষ সমর্থন সহ চাকুরীতে যোগদানের জন্য পুনরায় নির্দেশ প্রদান করেন। তৎসত্ত্বেও আপনি নির্ধারিত সময়ের মধ্যে আত্বপক্ষ
                সমর্থন করেননি এবং চাকুরীতে যোগদান করেননি।
            </p>
            <p class="b-font" style="margin-top: 20px;">সুতরাং বাংলাদেশ শ্রম-আইন ২০০৬ এর ২৭(৩ক) ধারা অনুযায়ী অনুপস্থিত দিন থেকে আপনি চাকরী হতে স্বেচ্ছায় অব্যাহতি গ্রহন করেছেন বলে
                গন্য করা হলো।</p>
            <p class="b-font" style="margin-top: 20px;">অতএব আপনার বকেয়া মজুরী ও আইনানুগ পাওনা (যদি থাকে) যে কোন কর্মদিবসে অফিস চলাকালীন সময়ে কারখানার
                হিসাব শাখা থেকে গ্রহন করার জন্য নির্দেশ দেওয়া গেল।
            </p>
        </div>

        <div class="letter_sign" style="margin-top: 60px;">
            @include('hrm::layouts.mpdf.sign')
        </div>
    </div>
</div>
@include('hrm::layouts.mpdf.footer')
