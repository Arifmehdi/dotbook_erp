<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">

</head>
<body>

    <section>
        <div class="row">
            <div class="form_element">
                <div class="section-header">
                    <span class="fas fa-table"></span>
                    <h6>@lang('menu.sales_order')</h6>
                </div>
                <div class="">
                    <div class="table-responsive">
                        <table id="sales_order_table" class="display data__table data_tble order_table" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>@lang('menu.date')</th>
                                    <th>@lang('menu.invoice_id')</th>
                                    <th>@lang('menu.business_location')</th>
                                    <th>@lang('menu.customer')</th>
                                    <th>@lang('menu.created_by')</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="py-2">
        <div class="row" class="display data__table data_tble order_table" cellspacing="0" width="100%">
            <table id="example">
                <tr>
                    <th>@lang('menu.name')</th>
                    <th>Age</th>
                </tr>
            </table>
        </div>
    </section>

    <hr>
    <hr>

    <script src="{{asset('plugins/jquery-3.6.0.js')}}"></script>
    <script src="//cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.24/b-1.7.0/b-colvis-1.7.0/b-html5-1.7.0/b-print-1.7.0/datatables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                dom: "lBfrtip"
                , "processing": true
                , "serverSide": true
                , lengthMenu: [3, 10, 20]
                , ajax: {
                    url: "{{ route('dev.empty_data') }}"
                }
                , columns: [{
                        data: 'name'
                        , name: 'name'
                    }
                    , {
                        data: 'age'
                        , name: 'age'
                    }
                ]
            });

            var sale_order_table = $('.order_table').DataTable({
        dom: "lBfrtip"
        , buttons: ["excel", "pdf", "print"]
        , "processing": true
        , "serverSide": true
        , aaSorting: [[0, 'asc']]
        , lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]]
        , "ajax": {
            "url": "{{ route('dashboard.sale.order') }}"
            , "data": function(d) {
                d.date_range = $('#date_range').val();
            }
        }
        , columns: [{
            data: 'date'
            , name: 'date'
        }, {
            data: 'invoice_id'
            , name: 'invoice_id'
        }, {
            data: 'from'
            , name: 'from'
        }, {
            data: 'customer'
            , name: 'customer'
        }, {
            data: 'created_by'
            , name: 'created_by'
        }]
    , });

        });

    </script>

</body>
</html>
