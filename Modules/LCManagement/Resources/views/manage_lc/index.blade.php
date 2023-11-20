@extends('layout.master')
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css"
        integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('title', 'LC Management - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>{{ __('Manage LC') }}</h6>
                </div>
                <x-all-buttons>
                    <x-add-button :text="'New Opening LC'" />
                    <x-slot name="after">
                        <x-help-button />
                    </x-slot>
                </x-all-buttons>
            </div>
        </div>
        <div class="p-15">
            @can('opening_lc_view')
                <div class="row">
                    <div class="col-md-12">
                        <div class="form_element rounded">
                            <div class="element-body">
                                <form id="filter_form">
                                    <div class="form-group row align-items-end g-2">
                                        <div class="col-xl-2 col-md-4">
                                            <label><strong>Open From Date </strong></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1"><i
                                                            class="fas fa-calendar-week input_f"></i></span>
                                                </div>
                                                <input type="text" name="from_date" id="datepicker"
                                                    class="form-control from_date date" autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="col-xl-2 col-md-4">
                                            <label><strong>Open To Date </strong></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1"><i
                                                            class="fas fa-calendar-week input_f"></i></span>
                                                </div>
                                                <input type="text" name="to_date" id="datepicker2"
                                                    class="form-control to_date date" autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="col-xl-2 col-md-4">
                                            <button type="submit" class="btn btn-sm btn-info"><i
                                                    class="fa-solid fa-filter-list"></i> @lang('menu.filter')</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan
            <div class="row mt-1">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="data_preloader">
                                <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                            </div>
                            @can('opening_lc_view')
                                <div class="table-responsive h-350" id="data-list">
                                    {{-- <table class="display data_tbl data__table table-hover"> --}}
                                    {{-- <table class="display data_tbl modal-table table-sm table-striped"> --}}
                                    <table class="display data_tbl data__table">
                                        <thead>
                                            <tr>
                                                <th class="text-start">@lang('menu.actions')</th>
                                                <th class="text-start">LC No</th>
                                                <th class="text-start">Opening Date</th>
                                                <th class="text-start">Last Date</th>
                                                <th class="text-start">Exp Date</th>
                                                <th class="text-start">@lang('menu.supplier')</th>
                                                <th class="text-start">Issuing Bank</th>
                                                <th class="text-start">Opening Bank</th>
                                                <th class="text-end">Total LC Amount</th>
                                                <th class="text-end">LC Margin Amt.</th>
                                                <th class="text-end">Insurance Payable.</th>
                                                <th class="text-end">Shipment Mode Amt.</th>
                                                <th class="text-end">Total LC Payable</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr class="bg-secondary">
                                                <th colspan="8" class="text-end text-white">@lang('menu.total') :
                                                    {{ json_decode($generalSettings->business, true)['currency'] }}
                                                </th>
                                                <th id="t_total_amount" class="text-end text-white"></th>
                                                <th id="t_lc_margin_amount" class="text-end text-white"></th>
                                                <th id="t_insurance_payable_amt" class="text-end text-white"></th>
                                                <th id="t_mode_of_amount" class="text-end text-white"></th>
                                                <th id="t_total_payable_amt" class="text-end text-white"></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            @endcan
                        </div>

                        <form id="deleted_form" action="" method="post">
                            @method('DELETE')
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="true"
        aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog col-45-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Opening LC</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_lc_form" action="{{ route('manage.lc.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <div class="col-xl-4 col-md-6">
                                <label><strong>LC No </strong> </label>
                                <input type="text" name="lc_no" class="form-control" id="name"
                                    placeholder="LC No" autocomplete="off" />
                            </div>

                            <div class="col-xl-4 col-md-6">
                                <label><strong>Opening Date </strong> <span class="text-danger">*</span></label>
                                <input type="text" name="opening_date" class="form-control add_input"
                                    data-name="Opening Date" id="opening_date" placeholder="DD-MM-YYYYY"
                                    autocomplete="off" />
                                <span class="error error_opening_date"></span>
                            </div>

                            <div class="col-xl-4 col-md-6">
                                <label><strong>Last Date </strong> <span class="text-danger">*</span></label>
                                <input type="text" name="last_date" class="form-control add_input"
                                    data-name="Last Date" id="last_date" placeholder="DD-MM-YYYYY" autocomplete="off" />
                                <span class="error error_last_date"></span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-xl-4 col-md-6">
                                <label><strong>@lang('menu.expire_date') </strong> <span class="text-danger">*</span></label>
                                <input type="text" name="expire_date" class="form-control add_input"
                                    data-name="Expire Date" id="expire_date" placeholder="DD-MM-YYYYY"
                                    autocomplete="off" />
                                <span class="error error_expire_date"></span>
                            </div>

                            <div class="col-xl-4 col-md-6">
                                <label><strong>@lang('menu.type') </strong></label>
                                <select name="type" class="form-control form-select">
                                    <option value="">@lang('menu.type')</option>
                                    <option value="1">Irrevocable LC</option>
                                    <option value="2">Revocable LC</option>
                                    <option value="3">Stand-by LC</option>
                                    <option value="4">Confirmed LC</option>
                                    <option value="5">Unconfirmed LC</option>
                                    <option value="6">Transferable LC</option>
                                    <option value="7">Back-to-Back LC</option>
                                    <option value="8">Payment at Sight LC</option>
                                    <option value="9">Deferred Payment LC</option>
                                    <option value="10">Red Clause LC</option>
                                </select>
                            </div>

                            <div class="col-xl-4 col-md-6">
                                <label><strong>Currency </strong> <span class="text-danger">*</span></label>
                                <select name="currency_id" class="form-control form-select" id="currency_id">
                                    <option value="">Select Currency</option>
                                    @foreach ($currencies as $currency)
                                        <option value="{{ $currency->id }}">{{ $currency->code }}</option>
                                    @endforeach
                                </select>
                                <span class="error error_currency_id"></span>
                            </div>
                        </div>

                        {{-- <div class="form-group row">
                            <div class="col-xl-4 col-md-6">
                                <label><strong>LC Amount </strong> <span class="text-danger">*</span></label>
                                <input type="number" step="any" name="lc_amount" class="form-control add_input" id="lc_amount" data-name="LC Amount" placeholder="LC Amount" autocomplete="off"/>
                                <span class="error error_lc_amount"></span>
                            </div>

                            <div class="col-xl-4 col-md-6">
                                <label><strong>Currency </strong></label>
                                <select name="currency" class="form-control form-select">
                                    <option value="1">USD</option>
                                    <option value="2">BDT</option>
                                </select>
                            </div>

                            <div class="col-xl-4 col-md-6">
                                <label><strong>Rate </strong> <span class="text-danger">*</span></label>
                                <input type="number" step="any" name="currency_rate" class="form-control add_input" id="currency_rate" data-name="Rate" placeholder="Rate" autocomplete="off"/>
                                <span class="error error_currency_rate"></span>
                            </div>
                        </div> --}}

                        {{-- <div class="form-group row mt-1">
                            <div class="col-xl-4 col-md-6">
                                <label><strong>@lang('menu.total_amount')(BDT) </strong> <span class="text-danger">*</span></label>
                                <input type="number" step="any" name="total_amount" class="form-control add_input" id="total_amount" data-name="@lang('menu.total_amount')(BDT)" placeholder="@lang('menu.total_amount')(BDT)" autocomplete="off"/>
                                <span class="error error_total_amount"></span>
                            </div>

                            <div class="col-xl-4 col-md-6">
                                <label><strong>LC Margin Amount </strong></label>
                                <input type="number" step="any" name="lc_margin_amount" class="form-control" id="lc_margin_amount" data-name="LC Margin Amount" placeholder="LC Margin Amount" autocomplete="off"/>
                            </div>
                        </div> --}}

                        {{-- <div class="form-group row mt-1">
                            <div class="col-xl-4 col-md-6">
                                <label><strong>Insurance Company</strong></label>
                                <input type="text" name="insurance_company" class="form-control" id="insurance_company" placeholder="Insurance Company Name" autocomplete="off"/>
                            </div>

                            <div class="col-xl-4 col-md-6">
                                <label><strong>Insurance Payable </strong> </label>
                                <input type="number" step="any" name="insurance_payable_amt" class="form-control" id="insurance_payable_amt" placeholder="Insurance Payable" autocomplete="off"/>
                            </div>
                        </div> --}}

                        {{-- <div class="form-group row mt-1">
                            <div class="col-xl-4 col-md-6">
                                <label><strong>Shipment Mode </strong></label>
                                <select name="shipment_mode" class="form-control form-select">
                                    <option value="1">C N F</option>
                                    <option value="2">FOB</option>
                                    <option value="3">FCA</option>
                                </select>
                            </div>

                            <div class="col-xl-4 col-md-6">
                                <label><strong>Mode Of Amount </strong> </label>
                                <input type="number" step="any" name="mode_of_amount" class="form-control" id="mode_of_amount" data-name="Mode Of Amount" placeholder="Mode Of Amount" autocomplete="off"/>
                            </div>

                            <div class="col-xl-4 col-md-6">
                                <label><strong>Total Lc Payable Amount </strong></label>
                                <input readonly type="text" name="total_payable_amt" class="form-control" id="total_payable_amt" data-name="Total Payable Amount" placeholder="Total Payable Amount" autocomplete="off"/>
                                <span class="error error_total_payable_amt"></span>
                            </div>
                        </div> --}}

                        {{-- <div class="form-group row mt-1">
                            <div class="col-xl-4 col-md-6">
                                <label><strong>@lang('menu.supplier') : </strong> <span class="text-danger">*</span></label>
                                <select name="supplier_id" class="form-control form-select" id="supplier_id" data-name="Supplier">
                                    <option value="">@lang('menu.select_supplier')</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->name.'('.$supplier->phone.')' }}</option>
                    @endforeach
                    </select>
                    <span class="error error_supplier_id"></span>
            </div>

            <div class="col-xl-4 col-md-6">
                <label><strong>Advising Bank </strong> <span class="text-danger">*</span></label>
                <select name="advising_bank_id" class="form-control form-select" id="advising_bank_id" data-name="Advising Bank">
                    <option value="">Select Advising Bank</option>
                    @foreach ($banks as $bank)
                    <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                    @endforeach
                </select>
                <span class="error error_advising_bank_id"></span>
            </div>

            <div class="col-xl-4 col-md-6">
                <label><strong>Issuing Bank </strong> <span class="text-danger">*</span></label>
                <select name="issuing_bank_id" class="form-control form-select" id="issuing_bank_id" data-name="Issuing Bank">
                    <option value="">Select Issuing Bank</option>
                    @foreach ($banks as $bank)
                    <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                    @endforeach
                </select>
                <span class="error error_issuing_bank_id"></span>
            </div>
        </div> --}}

                        {{-- <div class="form-group row mt-1">
                            <div class="col-xl-4 col-md-6">
                                <label><strong>Opening Bank </strong> <span class="text-danger">*</span></label>
                                <select name="opening_bank_id" class="form-control form-select" id="opening_bank_id" data-name="Opening Bank">
                                    <option value="">Select Opening Bank</option>
                                    @foreach ($banks as $bank)
                                        <option value="{{ $bank->id }}">{{ $bank->name }}</option>
        @endforeach
        </select>
        <span class="error error_opening_bank_id"></span>
    </div>
</div> --}}

                        <div class="form-group row mt-3">
                            <div class="col-md-12 d-flex justify-content-end">
                                <div class="loading-btn-box">
                                    <button type="button" class="btn btn-sm loading_button display-none"><i
                                            class="fas fa-spinner"></i></button>
                                    <button type="submit"
                                        class="btn btn-sm btn-success float-end submit_button">@lang('menu.save')</button>
                                    <button type="reset" data-bs-dismiss="modal"
                                        class="btn btn-sm btn-danger float-end me-2">@lang('menu.close')</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" data-bs-backdrop="static"
        data-bs-keyboard="true" aria-labelledby="staticBackdrop" aria-hidden="true"></div>

    <div id="lc_details"></div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js"
        integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        // Show session message by toster alert.
        @if (Session::has('successMsg'))
            toastr.success('{{ session('
                    successMsg ') }}');
        @endif

        var lc_table = $('.data_tbl').DataTable({
            "processing": true,
            "serverSide": true,
            dom: "lBfrtip",
            buttons: [{
                extend: 'pdf',
                text: '<i class="fa-thin fa-file-pdf fa-2x"></i><br>@lang('menu.pdf')',
                className: 'pdf btn text-white btn-sm px-1',
                exportOptions: {
                    columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
                }
            }, {
                extend: 'excel',
                text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>@lang('menu.excel')',
                className: 'pdf btn text-white btn-sm px-1',
                exportOptions: {
                    columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
                }
            }, {
                extend: 'print',
                text: '<i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')',
                className: 'pdf btn text-white btn-sm px-1',
                exportOptions: {
                    columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
                }
            }, ],
            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('manage.lc.index') }}",
                "data": function(d) {
                    d.supplier_id = $('#f_supplier_id').val();
                    d.issuing_bank_id = $('#f_issuing_bank_id').val();
                    d.opening_bank_id = $('#f_opening_bank_id').val();
                    d.from_date = $('.from_date').val();
                    d.to_date = $('.to_date').val();
                }
            },

            columns: [{
                    data: 'action'
                }, {
                    data: 'lc_no',
                    name: 'lc_no'
                }, {
                    data: 'opening_date',
                    name: 'opening_date'
                }, {
                    data: 'last_date',
                    name: 'last_date'
                }, {
                    data: 'expire_date',
                    name: 'expire_date'
                }, {
                    data: 'sup_name',
                    name: 'suppliers.name'
                }, {
                    data: 'issuing_bank',
                    name: 'issuing_bank.name'
                }, {
                    data: 'opening_bank',
                    name: 'opening_bank.name'
                }, {
                    data: 'total_amount',
                    name: 'total_amount',
                    className: 'text-end'
                }, {
                    data: 'lc_margin_amount',
                    name: 'lc_margin_amount',
                    className: 'text-end'
                }, {
                    data: 'insurance_payable_amt',
                    name: 'insurance_payable_amt',
                    className: 'text-end'
                }, {
                    data: 'mode_of_amount',
                    name: 'mode_of_amount',
                    className: 'text-end'
                }, {
                    data: 'total_payable_amt',
                    name: 'total_payable_amt',
                    className: 'text-end'
                },

            ],
            fnDrawCallback: function() {
                var total_amount = sum_table_col($('.data_tbl'), 'total_amount');
                $('#t_total_amount').text(bdFormat(total_amount));

                var lc_margin_amount = sum_table_col($('.data_tbl'), 'lc_margin_amount');
                $('#t_lc_margin_amount').text(bdFormat(lc_margin_amount));

                var insurance_payable_amt = sum_table_col($('.data_tbl'), 'insurance_payable_amt');
                $('#t_insurance_payable_amt').text(bdFormat(insurance_payable_amt));

                var mode_of_amount = sum_table_col($('.data_tbl'), 'mode_of_amount');
                $('#t_mode_of_amount').text(bdFormat(mode_of_amount));

                var total_payable_amt = sum_table_col($('.data_tbl'), 'total_payable_amt');
                $('#t_total_payable_amt').text(bdFormat(total_payable_amt));

                $('.data_preloader').hide();
            }
        });
        lc_table.buttons().container().appendTo('#exportButtonsContainer');

        function sum_table_col(table, class_name) {
            var sum = 0;

            table.find('tbody').find('tr').each(function() {
                if (parseFloat($(this).find('.' + class_name).data('value'))) {
                    sum += parseFloat(
                        $(this).find('.' + class_name).data('value')
                    );
                }
            });
            return sum;
        }

        //Submit filter form by select input changing
        $(document).on('submit', '#filter_form', function(e) {
            e.preventDefault();

            $('.data_preloader').show();
            lc_table.ajax.reload();
        });

        // Show details modal with data by clicking the row
        $(document).on('dblclick', 'tr.clickable_row td:not(:first-child)', function(e) {
            e.preventDefault();

            var url = $(this).parent().data('href');
            details(url);
        });

        // Pass sale details in the details modal
        function details(url) {

            $('.data_preloader').show();

            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {
                    $('#lc_details').html(data);
                    $('.data_preloader').hide();
                    $('#detailsModal').modal('show');
                }
            });
        }

        // Show details modal with data
        $(document).on('click', '.details_button', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');
            details(url);
        });

        // Make print
        $(document).on('click', '.print_btn', function(e) {
            e.preventDefault();

            var body = $('.lc_print_template').html();
            var header = $('.heading_area').html();

            $(body).printThis({
                debug: false,
                importCSS: true,
                importStyle: true,
                loadCSS: "{{ asset('css/print/sale.print.css') }}",
                removeInline: false,
                printDelay: 500,
                header: null,
                footer: null,
            });
        });

        $(document).on('click', '#delete', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');
            $('#deleted_form').attr('action', url);

            $.confirm({
                'title': 'Delete Confirmation',
                'content': 'Are you sure?',
                'buttons': {
                    'Yes': {
                        'class': 'yes btn-primary',
                        'action': function() {
                            $('#deleted_form').submit();
                        }
                    },
                    'No': {
                        'class': 'no btn-danger',
                        'action': function() {}
                    }
                }
            });
        });

        //Data delete by ajax
        $(document).on('submit', '#deleted_form', function(e) {
            e.preventDefault();

            var url = $(this).attr('action');
            var request = $(this).serialize();

            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {

                    lc_table.ajax.reload();
                    toastr.error(data);
                }
            });
        });

        //Add lc request by ajax
        $('#add_lc_form').on('submit', function(e) {
            e.preventDefault();

            $('.loading_button').show();
            var url = $(this).attr('action');

            $('.submit_button').prop('type', 'button');

            var request = $(this).serialize();
            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {

                    $('.error').html('');
                    $('.submit_button').prop('type', 'sumbit');
                    $('.loading_button').hide();

                    toastr.success(data);
                    $('#add_lc_form')[0].reset();
                    $('#addModal').modal('hide');
                    lc_table.ajax.reload();
                },
                error: function(err) {

                    $('.submit_button').prop('type', 'sumbit');
                    $('.loading_button').hide();
                    $('.error').html('');

                    if (err.status == 0) {

                        toastr.error('Net Connetion Error. Reload This Page.');
                        return;
                    } else if (err.status == 500) {

                        toastr.error('Server error. Please contact to the support team.');
                        return;
                    }

                    toastr.error('Please check again all form fields.', 'Some thing went wrong.');

                    $.each(err.responseJSON.errors, function(key, error) {

                        $('.error_' + key + '').html(error[0]);
                    });
                }
            });
        });

        // Pass editable data to edit modal fields
        $(document).on('click', '#edit', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');

            $.get(url, function(data) {

                $('#editModal').html(data);
                $('#editModal').modal('show');
                $('.data_preloader').hide();
            });
        });

        $(document).on('input', '#lc_amount', function(e) {

            calculateAmount();
        });

        $(document).on('input', '#currency_rate', function(e) {

            calculateAmount();
        });

        $(document).on('input', '#lc_margin_amount', function(e) {

            calculateAmount();
        });

        $(document).on('input', '#insurance_payable_amt', function(e) {

            calculateAmount();
        });

        $(document).on('input', '#mode_of_amount', function(e) {

            calculateAmount();
        });

        function calculateAmount() {

            var lc_amount = $('#lc_amount').val() ? $('#lc_amount').val() : 0;
            var rate = $('#currency_rate').val() ? $('#currency_rate').val() : 0;

            var totalAmount = parseFloat(lc_amount) * parseFloat(rate);
            $('#total_amount').val(parseFloat(totalAmount).toFixed(2));

            var lc_margin_amount = $('#lc_margin_amount').val() ? $('#lc_margin_amount').val() : 0;
            var insurance_payable_amt = $('#insurance_payable_amt').val() ? $('#insurance_payable_amt').val() : 0;
            var mode_of_amount = $('#mode_of_amount').val() ? $('#mode_of_amount').val() : 0;

            var totalPayableAmount = parseFloat(totalAmount) +
                parseFloat(lc_margin_amount) +
                parseFloat(insurance_payable_amt) +
                parseFloat(mode_of_amount);

            $('#total_payable_amt').val(parseFloat(totalPayableAmount).toFixed(2));
        }
    </script>

    <script type="text/javascript">
        new Litepicker({
            singleMode: true,
            element: document.getElementById('datepicker'),
            dropdowns: {
                minYear: new Date().getFullYear() - 50,
                maxYear: new Date().getFullYear() + 100,
                months: true,
                years: true
            },
            tooltipText: {
                one: 'night',
                other: 'nights'
            },
            tooltipNumber: (totalDays) => {
                return totalDays - 1;
            },
            format: 'DD-MM-YYYY'
        });

        new Litepicker({
            singleMode: true,
            element: document.getElementById('datepicker2'),
            dropdowns: {
                minYear: new Date().getFullYear() - 50,
                maxYear: new Date().getFullYear() + 100,
                months: true,
                years: true
            },
            tooltipText: {
                one: 'night',
                other: 'nights'
            },
            tooltipNumber: (totalDays) => {
                return totalDays - 1;
            },
            format: 'DD-MM-YYYY',
        });

        new Litepicker({
            singleMode: true,
            element: document.getElementById('opening_date'),
            dropdowns: {
                minYear: new Date().getFullYear() - 50,
                maxYear: new Date().getFullYear() + 100,
                months: true,
                years: true
            },
            tooltipText: {
                one: 'night',
                other: 'nights'
            },
            tooltipNumber: (totalDays) => {
                return totalDays - 1;
            },
            format: 'DD-MM-YYYY',
        });

        new Litepicker({
            singleMode: true,
            element: document.getElementById('last_date'),
            dropdowns: {
                minYear: new Date().getFullYear() - 50,
                maxYear: new Date().getFullYear() + 100,
                months: true,
                years: true
            },
            tooltipText: {
                one: 'night',
                other: 'nights'
            },
            tooltipNumber: (totalDays) => {
                return totalDays - 1;
            },
            format: 'DD-MM-YYYY',
        });

        new Litepicker({
            singleMode: true,
            element: document.getElementById('expire_date'),
            dropdowns: {
                minYear: new Date().getFullYear() - 50,
                maxYear: new Date().getFullYear() + 100,
                months: true,
                years: true
            },
            tooltipText: {
                one: 'night',
                other: 'nights'
            },
            tooltipNumber: (totalDays) => {
                return totalDays - 1;
            },
            format: 'DD-MM-YYYY',
        });
    </script>
@endpush
