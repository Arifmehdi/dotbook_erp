<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        @font-face {
            font-family: Product-Sans-Bold-italic;
            src: url(https://unpkg.com/aks-fonts@1.0.0/Product-Sans/Product-Sans-Bold-italic.ttf);
        }

        @font-face {
            font-family: Product-Sans-Bold;
            src: url(https://unpkg.com/aks-fonts@1.0.0/Product-Sans/Product-Sans-Bold.ttf);
        }

        @font-face {
            font-family: Product-Sans-Regular;
            src: url(https://unpkg.com/aks-fonts@1.0.0/Product-Sans/Product-Sans-Regular.ttf);
        }

        @font-face {
            font-family: Product-Sans-italic;
            src: url(https://unpkg.com/aks-fonts@1.0.0/Product-Sans/Product-Sans-italic.ttf);
        }

        body {
            padding: 0;
            margin: 0;
        }

        .cv div,
        .cv span,
        .cv p,
        .cv a,
        .cv span,
        .cv li {
            font-family: "Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", "Roboto",
                "Oxygen", "Ubuntu", "Cantarell", "Fira Sans", "Droid Sans", "Helvetica Neue",
                sans-serif;
            font-size: 16px;
            font-weight: normal;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            color: #000;
        }

        .container {
            width: 100%;
        }

        .card-body {
            width: 100%;
        }

        .row {
            display: flex;
            width: 100%;
            flex-direction: row;
        }

        .cv-header-title {
            float: left;
            width: 50%;
            margin-top: 20px;
        }

        .img {
            width: 50%;
            float: right;
        }

        .fix {
            display: table;
            content: "";
            clear: both;
        }

        .cv-header-title p {
            margin: 0;
            font-size: 13px;
        }

        .cv-header-title p a {
            font-size: 13px;
        }

        .img .avatar img {
            float: right;
            width: 150px;
            height: 150px;
            margin-top: -40px;
        }

        .cv-name {
            font-weight: bold;
        }

        .cv-subname {
            font-size: 13px;
        }

        .education,
        .experience {
            margin-top: 40px;
        }

        table {
            width: 100%;
        }

        table th {
            font-size: 13px;
            text-align: left;
            border: 1px solid #ddd;
        }

        table td {
            font-size: 11px;
            text-align: left;
            border: 1px solid #ddd;
        }
        .clear {
            display: table;
            content: "";
            clear: both;
        }
        .col-12 {
            display: block;
            width: 100%;
        }
        .footer_links div.title {
            display: inline-block;
            width: 100px;
            font-size: 13px;
            float: left;
        }
        .footer_links p {
            display: inline-block;
            float: left;
            font-size: 11px;
            margin-top: 0px;
        }
        .footer_links p a {
            font-size: 11px;
        }

    </style>
</head>

<body>
    <div class="container">
        <div class="card-header">
            <h4 class="card-title" id="staticBackdropLabel">{{ $apply->first_name }} {{ $apply->last_name }} ||
                @isset($job->job_title){{ $job->job_title }}@endisset </h4>
            </div>
            <div class="card-body">
                <div class="cv print">
                    <div class="cv-row">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-6 cv-header-title">
                                    <div class="cv-name">{{ $apply->first_name }} {{ $apply->last_name }}</div>
                                    @isset($job->job_title)<div class="cv-subname">{{ $job->job_title }}</div>@endisset
                                    <div class="info">
                                        <p><a href="mailto:{{ $apply->email }}">{{ $apply->email }}</a></p>
                                        <p><a href="tel:{{ $apply->phone }}">{{ $apply->phone }}</a></p>
                                        <p class="present_address">{{ $apply->location }} ,{{ $apply->city }}</p>
                                    </div>
                                </div>
                                <div class="col-6 text-center img">
                                    <div class="avatar">
                                        <img src="{{ asset($apply->photo) }}" alt="" />
                                    </div>
                                </div>
                            </div>
                            <div class="fix"></div>
                            <div class="row education">
                                <div class="col-12">
                                    <div class="title">Education</div>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col">Institue</th>
                                                <th scope="col">Department</th>
                                                <th scope="col">Degree</th>
                                                <th scope="col">Start</th>
                                                <th scope="col">End</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $educations = json_decode($apply->education);
                                            @endphp
                                            @isset($educations)
                                                @foreach ($educations as $edu)
                                                    <tr>
                                                        <td>{{ $edu->institute }}</td>
                                                        <td>{{ $edu->department }}</td>
                                                        <td><i>{{ $edu->degree }}</i></td>
                                                        <td>
                                                            <small>{{ $edu->edu_start_month }} {{ $edu->edu_start_year }}</small>
                                                        </td>
                                                        <td>
                                                            <small>{{ $edu->edu_end_month }} {{ $edu->edu_end_year }}</small>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endisset
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row experience">
                                <div class="col-12">
                                    <div class="title">Exprecince</div>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col">Company</th>
                                                <th scope="col">Designation</th>
                                                <th scope="col">Summary</th>
                                                <th scope="col">Start</th>
                                                <th scope="col">End</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $experiences = json_decode($apply->experience);
                                            @endphp
                                            @isset($experiences)
                                                @foreach ($experiences as $exp)
                                                    <tr>
                                                        <td>{{ $exp->company }}</td>
                                                        <td>{{ $exp->designation }}</td>
                                                        <td>{{ $exp->summary }}</td>
                                                        <td>
                                                            {{ $exp->exp_start_month }} {{ $exp->exp_start_year }}
                                                        </td>
                                                        <td>
                                                            {{ $exp->exp_end_month }} {{ $exp->exp_end_year }}
                                                        </td>
                                                    </tr>
                                                @endforeach

                                            @endisset
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="footer_links">
                                    <div class="title strong">Skills</div>
                                    <p><small>{{ $apply->skill }}</small></p>
                                </div>
                            </div>
                            <div class="clear"></div>
                            <div class="row">
                                <div class="footer_links">
                                    <div class="title strong">Website</div>
                                    <p><small><a href="{{ $apply->website_url }}">{{ $apply->website_url }}</a></small></p>
                                </div>
                            </div>
                            <div class="clear"></div>
                            <div class="row">
                                <div class="footer_links">
                                    <div class="title strong">Linkedin Url</div>
                                    <p><small><a href="{{ $apply->linkedin_url }}">{{ $apply->linkedin_url }}</a></small></p>
                                </div>
                            </div>
                            {{-- <div class="row">
                                <div class="col-6">
                                    <div class="title">Resume</div>
                                    <p>
                                        @if ($apply->resume != null)
                                            <a target="_blank" href="{{ asset($apply->resume) }}"><span
                                                    class="badge bg-success">View</span></a>
                                        @else
                                            <span class="badge bg-danger">No File</span>
                                        @endif
                                    </p>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>

    </html>
