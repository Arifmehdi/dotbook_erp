@include('hrm::layouts.mpdf.header')

<style>
table {
    padding-top: 20px;
}
td, td p {
    font-size: 18px;
    line-height: 18px;
}
</style>

<div class="main" style="">
    <div class="main_content_wrapper">
        @include('hrm::layouts.mpdf.address')

        <div class="letter_body" style="font-size: 18px; line-height:18px;">
            <p class="bisoy b-font" style="margin-top: 20px;"><b>বিষয়ঃ </b> বাংলাদেশ শ্রম-আইন ২০০৬ এর ২৭(৩ক) ধারা মোতাবেক ব্যাখ্যা প্রদান সহ চাকুরীতে যোগদানের নোটিশ ।</p>
            <p class="b-font" style="margin-top: 10px;">জনাব,</p>
            <p class="b-font" style="margin-top: 6px;">আপনি গত ................................................. তারিখ থেকে কারখানা কতৃপক্ষ্যের বিনা অনুমতিতে কর্মস্থলে অনুপস্থিত রয়েছেন। আপনার এরুপ অনুপস্থিতি
                বাংলাদেশের শ্রম-আইন ২০০৬ এর ২৭(ক) ধারার আওতায় পড়ে।
            </p>
            <p class="b-font" style="margin-top: 20px;">সুতরাং অত্র পত্র পাপ্তির ১০(দশ) দিনের মধ্যে আপনার অনুপস্থিতির কারন ব্যাখ্যা সহ কাজে যোগদানের জন্য নির্দেশ দেওয়া হলো।</p>
            <p class="b-font" style="margin-top: 20px;">আপনার লিখিত জবাব উক্ত সময়ের মধ্যে নিম্নস্বাক্ষরকারীর নিকট অবশ্যই পৌছাতে হবে। অন্যথায় কর্তৃপক্ষ আপনার বিরুদ্ধে প্রয়োজনীয়
                আইনানুগত ব্যবস্থা নিতে বাধ্য হবে।
            </p>
        </div>

        <div class="letter_sign" style="margin-top: 60px;">
            @include('hrm::layouts.mpdf.sign')
        </div>
    </div>
</div>
@include('hrm::layouts.mpdf.footer')
