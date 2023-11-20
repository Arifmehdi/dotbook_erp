@extends('layout.master')
@push('css')
    <link href="{{ asset('css/tab.min.css') }}" rel="stylesheet" type="text/css" />

    {{-- select 2 --}}
    

    <style>
        button.btn.btn-danger.deletewarrantyButton {
            border-radius: 0px !important;
            padding: 0.7px 10px !important;
        }
    </style>
@endpush
@section('title', 'CRM - ')
@section('content')
    <div class="body-wraper">
        <div class="container-fluid p-0">
            <div class="p-15">
                <form id="submit_proposal_view_form"
                    action="{{ route('crm.proposal_template.accept', $proposal_template->id) }}" method="POST"
                    enctype="multipart/form-data" class="row g-1">
                    @csrf
                    <div class="col-12">
                        <div class="card mb-0">
                            <div class="card-body p-2">
                                <div class="row g-1">
                                    <div class="col-md-6">
                                        <p><strong>{{ 'prop ' . $proposal_template->proposal_id }}</strong></p>
                                    </div>
                                    <div class="col-md-6 d-flex justify-content-end">
                                        <div class="loading-btn-box">
                                            <button type="button" class="btn btn-sm loading_button display-none"><i
                                                    class="fas fa-spinner"></i></button>
                                            <button class="btn btn-sm btn-primary" id="download_btn"
                                                data-confirm_btn="{{ $proposal_template->proposal_id }}">Download</button>
                                            @if ($proposal_template->status == 1)
                                                <button class="btn btn-sm btn-primary hide-btn ms-2" id="dicline_btn"
                                                    data-confirm_btn="{{ $proposal_template->proposal_id }}">Dicline</button>
                                                <button type="submit" class="btn btn-sm btn-primary hide-btn ms-2"
                                                    id="accept_btn"
                                                    data-confirm_btn="{{ $proposal_template->proposal_id }}">Accept</button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="card mb-0">
                            <div class="card-body p-3">
                                <h3>Company info</h3>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Porro provident aliquid dolore
                                    repellat mollitia adipisci,nostrum voluptatum, expedita obcaecati, unde consequuntur
                                    esse nesciunt similique ad. Eaque dicta earum repudiandae mollitia. Lorem ipsum dolor
                                    sit amet, consectetur adipisicing elit. Porro provident aliquid dolore repellat mollitia
                                    adipisci,nostrum voluptatum, expedita obcaecati, unde consequuntur esse nesciunt
                                    similique ad. Eaque dicta earum repudiandae mollitia. Lorem ipsum dolor sit amet,
                                    consectetur adipisicing elit. Porro provident aliquid dolore repellat mollitia
                                    adipisci,nostrum voluptatum, expedita obcaecati, unde consequuntur esse nesciunt
                                    similique ad. Eaque dicta earum repudiandae mollitia.</p>
                                <h3>Company privacy</h3>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Porro provident aliquid dolore
                                    repellat mollitia adipisci,nostrum voluptatum, expedita obcaecati, unde consequuntur
                                    esse nesciunt similique ad. Eaque dicta earum repudiandae mollitia. Lorem ipsum dolor
                                    sit amet, consectetur adipisicing elit. Porro provident aliquid dolore repellat mollitia
                                    adipisci,nostrum voluptatum, expedita obcaecati, unde consequuntur esse nesciunt
                                    similique ad. Eaque dicta earum repudiandae mollitia. Lorem ipsum dolor sit amet,
                                    consectetur adipisicing elit. Porro provident aliquid dolore repellat mollitia
                                    adipisci,nostrum voluptatum, expedita obcaecati, unde consequuntur esse nesciunt
                                    similique ad. Eaque dicta earum repudiandae mollitia.</p>
                                <img src="" alt="Image">
                                <h4 class="mb-2">{{ $proposal_template->subject }}</h4>
                                <div class="table-responsive">
                                    <table class="table" id="myTable">
                                        <thead>
                                            <tr>
                                                <th class="px-1">No.</th>
                                                <th class="px-1">Name</th>
                                                <th class="px-1">Description</th>
                                                <th class="px-1">Qty</th>
                                                <th class="px-1">Rate</th>
                                                <th class="px-1">Exc./Inc. </th>
                                                <th class="px-1">Tax</th>
                                                <th class="px-1">Discount</th>
                                                <th class="px-1">Type</th>
                                                <th class="px-1">Amount</th>
                                                {{-- <th><i class="fa fa-cog" aria-hidden="true"></i></th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($proposal_template->proposal_details as $key => $details)
                                                <tr>
                                                    <td class="px-1">{{ $key + 1 }}</td>
                                                    <td class="px-1">{{ $details->name }}</td>
                                                    <td class="px-1">
                                                        {{ $details->details != 'null' ? $details->details : '' }}</td>
                                                    <td class="px-1">
                                                        <input type="text" class="form-control" name="qty[]"
                                                            id="qty_{{ $details->id }}" value="{{ $details->qty }}"
                                                            onfocusout="focusoutCustom({{ $details->id }})">
                                                        <input type="hidden" name="amount[]"
                                                            id="inputAmount_{{ $details->id }}"
                                                            value="{{ $details->amount }}">
                                                        <input type="hidden" name="details_id[]"
                                                            value="{{ $details->id }}">
                                                        <input type="hidden" name="item_id[]"
                                                            value="{{ $details->item_id }}">
                                                    </td>
                                                    <td class="px-1">
                                                        <input type="text" class="form-control" name="rate[]"
                                                            id="rate_{{ $details->id }}" value="{{ $details->rate }}"
                                                            readonly>
                                                    </td>
                                                    <td class="px-1">
                                                        {{ $details->tax_type == 1 ? 'Exclusive' : 'Inclusive' }}</td>
                                                    <td class="px-1">
                                                        {{ $details->tax != 0 ? $details->tax . '%' : 'N/A' }}
                                                    </td>
                                                    <td class="px-1">
                                                        {{ $details->discount != 0 ? $details->discount : '0' }}</td>
                                                    <td class="px-1">
                                                        {{ $details->discount_type == 1 ? 'Fixed' : 'Percentage' }}</td>
                                                    <td class="px-1" id="amount_{{ $details->id }}">
                                                        {{ $details->amount }}</td>
                                                    {{-- <td>
                                                <button class="btn btn-sm btn-danger px-2" type="button" onclick="this.parentElement.parentElement.remove()"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                            </td> --}}
                                                </tr>
                                            @empty
                                                <span>Data ton found</span>
                                            @endforelse
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="8"></td>
                                                <td>Sub-Total</td>
                                                <td>
                                                    <input type="text" class="form-control" name="subTotalAmount"
                                                        id="subTotalAmount" value="{{ $proposal_template->sub_total }}"
                                                        readonly>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="8"></td>
                                                <td>Discount</td>
                                                <td>
                                                    <input type="text" class="form-control" name="totalDiscount"
                                                        id="totalDiscount" value="{{ $proposal_template->discount }}"
                                                        readonly>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="8"></td>
                                                <td>Total</td>
                                                <td>
                                                    <input type="text" class="form-control" name="totalAmount"
                                                        id="totalAmount" value="{{ $proposal_template->total }}"
                                                        readonly>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body p-3">
                                <div class="tab_list_area">
                                    <ul class="nav list-unstyled mb-3" role="tablist">
                                        <li>
                                            <a id="tab_btn" data-show="summary" class="tab_btn tab_active"
                                                href="#">
                                                Summary
                                            </a>
                                        </li>
                                        <li>
                                            <a id="tab_btn" data-show="discussion" class="tab_btn" href="#">
                                                Discussion
                                            </a>
                                        </li>
                                    </ul>
                                </div>

                                <div class="tab_contant active summary" id="summary">
                                    <div class="tab-content-inner">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <h4>Dotbook ERP</h4>
                                                <p>Uttora, Dhaka, Bangladesh</p>
                                                <hr>
                                                <h6>Proposal Information</h6>
                                                <p> <strong>{{ $proposal_template->to }}</strong>,
                                                    {{ $proposal_template->address . ' ' }}
                                                    {{ $proposal_template->city . ' ' }}
                                                    {{ $proposal_template->state . ' ' }}
                                                    {{ $proposal_template->zip . ' ' }}
                                                    {{ $proposal_template->country }}</p>
                                                <p>{{ $proposal_template->zip }}</p>
                                                <p><a
                                                        href="tel::{{ $proposal_template->phone }}">{{ $proposal_template->phone }}</a>
                                                </p>
                                                <p><a
                                                        href="mailto::{{ $proposal_template->email }}">{{ $proposal_template->email }}</a>
                                                </p>
                                                <p><strong>Total $ {{ $proposal_template->total }}</strong></p>

                                                <table>
                                                    <tr>
                                                        <td>Status</td>
                                                        <td></td>
                                                        <td>Open</td>
                                                    <tr>
                                                    <tr>
                                                        <td>Date</td>
                                                        <td></td>
                                                        <td>{{ $proposal_template->date }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Open Till</td>
                                                        <td></td>
                                                        <td>{{ $proposal_template->open_till }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab_contant d-none discussion" id="discussion">
                                    <div class="tab-content-inner">
                                        <div class="row">
                                            <div class="col-sm-12 p-1 m-1" id="allComment"></div>
                                            <div class="col-sm-12">
                                                <div class="col-sm-12">
                                                    <input type="hidden" class="" id="p_id"
                                                        value="{{ $proposal_template->id }}">
                                                    <textarea name="inputedComment" class="form-control ckEditor" id="inputedComment" cols="30" rows="4"></textarea>
                                                </div>
                                                <div class="col-md-12 d-flex justify-content-end">
                                                    <button class="btn btn-sm btn-primary p-1" id="sendComment">Add
                                                        Comment</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/35.0.1/classic/ckeditor.js"></script>

    <script>
        function getComments() {
            var url = "{{ route('crm.proposal_template.comment.get', ['propoId' => ':propoId']) }}";
            url = url.replace(':propoId', $('#p_id').val());
            $.get(url, function(data) {
                $('#allComment').empty();
                data.forEach(element => {
                    $('#allComment').append(
                        "<span class='comment card card-body bg-secondary-subtle px-1 py-0 m-1'>" +
                        element.comments + "</span>")
                });
            })
        }

        $(document).ready(function() {
            getComments();
            $('#sendComment').on('click', function(e) {
                e.preventDefault();
                var id = $('#p_id').val();
                var comment = $('#inputedComment').val();
                var url = "{{ route('crm.proposal_template.comment', ['propoId' => ':propoId']) }}";
                url = url.replace(':propoId', id);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "post",
                    url: url,
                    dataType: "json",
                    data: {
                        comment: comment,
                        id: id
                    },
                    success: function(data) {
                        toastr.success(data.success);
                        getComments();
                        $('#inputedComment').val("");
                    }
                });
            });


            $('#accept_btn').on('click', function() {
                $('#submit_proposal_view_form').submit(function(e) {
                    e.preventDefault();
                    $('.loading_button').show();
                    var url = $(this).attr('action');
                    // var request = $("#submit_proposal_view_form").serialize();
                    var request = new FormData(this);
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: url,
                        type: 'post',
                        data: request,
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function(data) {
                            toastr.success(data);
                            $('.hide-btn').addClass('d-none');
                            $('.loading_button').hide();
                        },
                        error: function(err) {
                            $('.loading_button').hide();
                            $('.error').html('');
                            if (err.status == 0) {
                                toastr.error('Net Connetion Error. Reload This Page.');
                                return;
                            }
                            $.each(err.responseJSON.errors, function(key, error) {
                                $('.error_' + key + '').html(error[0]);
                            });
                        }
                    });
                });
            });

            $('#dicline_btn').on('click', function(e) {
                var show_id = $(this).data('confirm_btn');
                $('.loading_button').show();
                var url = "{{ route('crm.proposal_template.decline', ['propoId' => ':propoId']) }}";
                url = url.replace(':propoId', show_id);
                $.get(url, function(data) {
                    $('.hide-btn').addClass('d-none');
                    toastr.success(data);
                });
            });
        });

        function focusoutCustom(id) {
            var qty = document.getElementById('qty_' + id).value;
            var rate = document.getElementById('rate_' + id).value
            var rowRate = rate *= qty;
            // $("inputAmount_" + id).val(rowRate);
            document.getElementById('inputAmount_' + id).value = rowRate;
            document.getElementById('amount_' + id).innerText = rowRate;
            calculateVal();
        }

        function calculateVal() {
            var totalVal = 0;
            $("#myTable tbody tr").each(function() {
                var data = $(this).find('td').eq(9).text();
                totalVal += parseFloat(data);
            })
            $("#subTotalAmount").val(totalVal);
            var totalWithoutDiscount = totalVal - $("#totalDiscount").val();
            $("#totalAmount").val(totalWithoutDiscount);
        }

        $(document).on('click', '#tab_btn', function(e) {
            e.preventDefault();
            // use data attribute
            $('.tab_btn').removeClass('tab_active');
            $('.tab_contant').addClass('d-none');
            var show_content = $(this).data('show');

            $('.' + show_content).removeClass('d-none');
            $(this).addClass('tab_active');
        });
    </script>
@endpush
