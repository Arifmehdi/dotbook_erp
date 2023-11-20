
    <!-- CSS & Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.maateen.me/solaiman-lipi/font.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
    <title>Official Lefty First Letter</title>

    <style>
        /* Global CSS */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'SolaimanLipi', sans-serif;
        }
        .small-text {
            font-size: 10px;
        }
        .main {
            box-sizing: border-box;
            width: 100%;
            display: inline-block;
            padding: 20px 30px;
        }
        .main_content_wrapper {
            width: 100%;
        }
        .b-font {
            font-size: 12px;
        }
        table, th, td, tr {
            border-collapse: collapse;
        }
        td {
            margin: 0;
            padding: 0;
            vertical-align: top;
        }
        .no-wrap{
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
            -moz-tab-size: 4; /* Firefox */
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
        .fix {
            content: "";
            clear: both;
        }
        /* Header style */
        #header {
            width: 100%;
        }
        .header_wrapper {
            display: flex;
            width: 59%;
            margin: 0 auto;
        }
        .logo img {
            width: 120px;
            margin-top: 10px;
        }
        h1.company_name {
            white-space: nowrap;
            text-transform: capitalize;
            color: #05172c;
            display: inline-block;
            font-weight: 600;
            transform: scale(.9,1.5);;
            margin-left: -23px;
        }
        .company_title {
            margin-left: 20px;
        }
        p.company_desc {
            text-align: center;
            font-size: 13px;
            color: #000;
            margin-top: 18px;
            font-weight: 600;
            margin-left: -10px;
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
            font-size: 12px;
            width: 33%;
            float: left;;
        }
        .present_address td,
        .permanent_address td {
            font-size: 12px;
        }
        table.salary td {
            border: 1px solid;
            text-align: center;
            padding: 8px 4px;
        }
        .letter_body {
            line-height: 1.6rem;
        }
    </style>

</head>
<body>
    <div class="main">
        <div class="main_content_wrapper">

            <!-- Header -->
           <div id="header">
                <div class="header_wrapper">
                    <div class="company_title">
                        <h1 class="company_name">Brickland Composite Ltd.</h1>
                        <p class="company_desc">Manufacturer & Exporter of Quality Garments</p>
                    </div>
                </div>
           </div>

           <div style="width: 100%; display: block; border-bottom: 2px solid black; margin-top: 10px;"></div>

           <div class="line_one">
               <table class="d-block">
                   <tr class="d-block">
                       <td style="width: 40%;">
                            <p class="my-1">তারিখঃ <span class="relative"> {{ date('d') }} , {{ date('F') }} {{ date('Y') }} <span class="absolute d-content-span"></span></span></p>
                            <p>কার্ড নংঃ <span class="relative">{{ $employee->employee_id }}<span class="absolute d-content-span"></span></span></p>
                        </td>
                       <td style="width: 27%;display: block;">&nbsp;</td>
                       <td style="width: 33%;"><p style="text-align: right; margin-top: 10px;">"রেজিষ্ট্রি ডাক যোগে প্রেরিত"</p></td>
                   </tr>
               </table>
               <div class="fix"></div>
               <table class="d-block">
                   <tr class="d-block">
                       <td style="width: 50%;">
                            <p class="my-1">নামঃ  <span class="relative">{{ $employee->name }}<span class="absolute d-content-span"></span></span></p>
                        </td>
                       <td style="width: 30%;"><p class="my-1" style="text-align: right;">পিতার নামঃ <span class="relative">{{ $employee->father_name }}<span class="absolute d-content-span"></span></span></p></td>
                   </tr>
               </table>
           </div>

           <div class="fix"></div>

           <div class="present_address mt-1">
               <table class="d-block">
                   <tr class="d-block">
                       <td style="width:30%;"><b>বর্তমান ঠিকানাঃ &nbsp; </b> </td>
                       <td >
                          {{ $employee->present_village }}
                       </td>
                   </tr>
               </table>
           </div>

           <div class="permanent_address" style="margin-top: 10px;">
               <table class="d-block">
                   <tr class="d-block">
                       <td style="width: 100px;"><b>স্থায়ী ঠিকানাঃ </b></td>
                       <td style="width: 45%;">
                           {{-- <p>গ্রামঃ <span class="relative">{{ $employee->village}}<span class="absolute d-content-span"></span></span></p> --}}
                           <p>গ্রামঃ <span class="relative"><?php echo ($employee->village == '') ? $employee->permanent_village : ''; ?><span class="absolute d-content-span"></span></span></p>
                           {{-- <p class="my-1">জেলাঃ  <span class="relative">{{ $employee->district }}<span class="absolute d-content-span"></span></span></p> --}}
                           <p class="my-1">জেলাঃ <span class="relative"><?php echo ($employee->district == '') ? $BdDistrict : ''; ?><span class="absolute d-content-span"></span></span></p>
                        </td>
                       <td style="width: 45%;">
                           {{-- <p>পোষ্টঅফিস : <span class="relative">{{ $employee->postoffice }}<span class="absolute d-content-span"></span></span></p> --}}
                           <p>পোষ্টঅফিসঃ <span class="relative"><?php echo ($employee->postoffice == '') ? $BdUnion : ''; ?><span class="absolute d-content-span"></span></span></p>
                           {{-- <p class="my-1">উপজেলাঃ <span class="relative">{{ $employee->thana }}<span class="absolute d-content-span"></span></span></p> --}}
                           <p class="my-1">উপজেলাঃ <span class="relative"><?php echo ($employee->thana == '') ? $BdUpazila : ''; ?><span class="absolute d-content-span"></span></span></p>
                        </td>
                   </tr>
               </table>
           </div>

           <div class="letter_body">
                <p class="bisoy b-font" style="margin-top: 20px;"><b>বিষয়ঃ </b> বাংলাদেশ শ্রম-আইন ২০০৬ এর ২৭(৩ক) ধারা মোতাবেক ব্যাখ্যা প্রদান সহ চাকুরীতে যোগদানের নোটিশ ।</p>
                <p class="b-font" style="margin-top: 10px;">জনাব,</p>
                <p class="b-font" style="margin-top: 6px;">আপনি গত <span class="relative"> {{ $date }} <span class="absolute d-content-span"></span> </span> তারিখ থেকে কারখানা কতৃপক্ষ্যের বিনা অনুমতিতে কর্মস্থলে অনুপস্থিত রয়েছেন। আপনার এরুপ অনুপস্থিতি
                    বাংলাদেশের শ্রম-আইন ২০০৬ এর ২৭(ক) ধারার আওতায় পড়ে।
                </p>
                <p class="b-font" style="margin-top: 20px;">সুতরাং অত্র পত্র পাপ্তির ১০(দশ) দিনের মধ্যে আপনার অনুপস্থিতির কারন ব্যাখ্যা সহ কাজে যোগদানের জন্য নির্দেশ দেওয়া হলো।</p>
                <p class="b-font" style="margin-top: 20px;">আপনার লিখিত জবাব উক্ত সময়ের মধ্যে নিম্নস্বাক্ষরকারীর নিকট অবশ্যই পৌছাতে হবে। অন্যথায় কর্তৃপক্ষ আপনার বিরুদ্ধে প্রয়োজনীয়
                    আইনানুগত ব্যবস্থা নিতে বাধ্য হবে।
                </p>
           </div>

           <div class="letter_sign" style="margin-top: 60px; line-height: 1.4rem;">
               <p><span class="relative" style="color: black;">_____________<span class="absolute d-content-span"></span></span></p>
               <p class="b-font"><b>এস এম আল-আমিন</b></p>
               <p class="b-font">ম্যানেজার । এইচ আর এন্ড কমপ্লায়েন্স</p>

               <p class="b-font">অনুলিপিঃ </p>
               <p class="b-font">০১। ব্যবস্থাপনা পরিচালক, ডিজনী সোয়েটার লিঃ</p>
               <p class="b-font">০২। কারখানা নোটিশ বোর্ড</p>
               <p class="b-font">০৩। শ্রমিকের ব্যক্তিগত নথি</p>
           </div>

           <div class="footer" style="margin-top: 100px;">
               <p class="b-font" style="text-align: center; padding-top: 20px; border-top: 3px solid black;">Head Office: House # 17, Shah Mukhdum Avenue, Sector #04, Uttara, Dhaka-1230, Bangladesh. Phone: 02-55085571-3
                <br> Factory: Barpa, Rupshi (1464), Rupganj, Narayanganj, Bangladesh.
               </p>
           </div>

        </div>
    </div>
