<!-- CSS & Fonts -->
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.maateen.me/solaiman-lipi/font.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
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

    .main_content_wrapper {
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
        font-family: monospace;
    }

    .small-text {
        font-size: 8px !important;
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

<div class="main page-break">
    <div class="main_content_wrapper">
        <div id="header">
            <div class="header_wrapper">
                <div class="row">
                    <div class="col-5">
                        <div class="logo"><img src="{{ asset('images/favicon.png') }}" alt="Company logo" style="height: 40px;"></div>
                        <div class="company_title">
                            <h1 class="company_name">{{ json_decode($settings->business)->shop_name }}</h1>
                            <p class="company_desc">ERP - Enterprise resource planning</p>
                        </div>
                    </div>


                    <div class="col-7">
                        <div class="mt-20 monospace" style="padding-top: 20px;">
                            <p>
                                <b>Head Office: </b> {{   json_decode($settings->business)->address }} <br>
                            </p>
                            <p>
                                <b>Factory: </b> {{  json_decode($settings->business)->address }} <br>
                            </p>
                            <p>
                                <b>Hotline: </b> {{  json_decode($settings->business)->phone }}<br> <br>
                                <b>Web: </b> {{  json_decode($settings->business)->email }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<hr>
