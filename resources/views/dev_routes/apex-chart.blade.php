@extends('layout.master')

@push('css')
    <style>
        @import url(https://fonts.googleapis.com/css?family=Roboto);

        body {
            font-family: Roboto, sans-serif;
        }

        #chart {
            max-width: 650px;
        }
    </style>
@endpush
@section('content')
    <div class="container">
        <div id="chart"></div>
    </div>

    @push('js')
        <script>
            var options = {
                chart: {
                    type: 'bar'
                },
                series: [{
                    name: 'sales',
                    data: [30, 40, 45, 50, 49, 60, 70, 91, 125]
                }],
                xaxis: {
                    categories: [1991, 1992, 1993, 1994, 1995, 1996, 1997, 1998, 1999]
                }
            }

            var chart = new ApexCharts(document.querySelector("#chart"), options);

            chart.render();
        </script>
    @endpush
@endsection
