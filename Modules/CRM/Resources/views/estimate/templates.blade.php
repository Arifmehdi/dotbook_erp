@extends('layout.master')
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css"
        integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        button.btn.btn-danger.deletewarrantyButton {
            border-radius: 0px !important;
            padding: 0.7px 10px !important;
        }

        .border-right-css {
            border-right: 1px solid #3cb7dd;
        }

        .card-header {
            border: none !important;
        }

        .background-color {
            background-color: #b6e5f8c7;
        }

        .section {
            padding: 8px;
            border: 1px solid rgb(95, 95, 95);
            border-radius: 5px;
        }
    </style>
@endpush
@section('title', 'Estimate - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>Estimate</h6>
                </div>
                <div class="d-flex">
                    <div id="exportButtonsContainer">
                        @can('asset_create')
                            <a href="#" data-bs-toggle="modal" data-bs-target="#addModal" class="btn text-white btn-sm"><i
                                    class="fa-thin fa-circle-plus fa-2x"></i><br>@lang('menu.add_new')</a>
                        @endcan
                    </div>
                    <a href="#" class="btn text-white btn-sm d-lg-block d-none"><span
                            class="fas fa-thin fa-circle-question fa-2x"></span><br>@lang('menu.help')</a>
                </div>
                <div>
                    <a href="{{ url()->previous() }}" class="btn text-white btn-sm  float-end back-button"><i
                            class="fa-thin fa-left-to-line fa-2x"></i><br>@lang('menu.back')
                    </a>
                </div>
            </div>
            <div class="p-15">
                <div class="row">
                    <div class="col-9">
                        <div class="row bg-white">
                            <h1 class="text-center">Proposal</h1>
                            <div class="col-md-6">
                                <div>
                                    <img src="{{ asset('images/asbrm_logo.png') }}" alt="">
                                </div>
                            </div>
                            <div class="justify-content-end d-flex">
                                <div class="col-md-3">
                                    <form>
                                        <div class="form-group row">
                                            <label for="staticEmail" class="col-sm-2 me-1 col-form-label">Email</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control">
                                            </div>
                                            <label for="staticEmail" class="col-sm-2 me-1 col-form-label">Email</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control">
                                            </div>
                                            <label for="staticEmail" class="col-sm-2 me-1 col-form-label">Email</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-6 border-right-css">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-6 p-2">
                                            <h5>Sender</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-body background-color">
                                        <div class="justify-content-center d-flex">
                                            <a href="#" class="p-5" data-bs-toggle="modal"
                                                data-bs-target="#senderModal">Add new</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-6 mt-2">
                                            <h5>Receiver</h5>
                                        </div>
                                        <div class="col-6">
                                            <select name="" id=""
                                                class="form-control-sm float-end form-select">
                                                <option value="">Date</option>
                                                <option value="">Time</option>
                                                <option value="">Date</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-body background-color">
                                        <div class="justify-content-center d-flex">
                                            <a href="#" class="p-5">Member</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="section">
                                    <div id="section_area">
                                        <div>
                                            <label><strong>@lang('menu.description')</strong></label>
                                            <button class="btn btn-danger deletewarrantyButton float-end mb-1"
                                                type="button" onclick="this.parentElement.remove()">X</button>
                                            <textarea name="description" class="w-100 ckEditor"></textarea>
                                        </div>
                                    </div>
                                    <div class="justify-content-center d-flex">
                                        <button type="button" class="btn btn-primary mb-2 p-1 btn-sm" id="addSection"><i
                                                class="fas fa-plus"></i> Add New Section</button>
                                    </div>
                                </div>
                                {{-- new --}}
                                <div class="row mt-2" id="item_area">
                                    <div class="col-md-4">
                                        <label><strong>Title </strong> </label>
                                        <input type="text" required name="asset_name" class="form-control add_input"
                                            data-name="Asset Name" id="Asset_name" placeholder="Asset Name" />
                                        <span class="error error_asset_name"></span>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label><strong>Quantity </strong> </label>
                                                <div class="input-group">
                                                    <input type="number" step="any" name="item_qty"
                                                        class="form-control item_value" data-name="" id="item_qty"
                                                        placeholder="0" value="0">
                                                    <select name="balance_type" class="form-control form-select">
                                                        <option value="">-- Select --</option>
                                                        <option value="1">Pics</option>
                                                        <option value="2">kg</option>
                                                        <option value="3">unit</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label><strong>Rate </strong> </label>
                                                <input type="number" name="item_rate"
                                                    class="form-control add_input item_value" id="item_rate"
                                                    placeholder="0" value="0" />
                                            </div>
                                            <div class="col-md-4">
                                                <label><strong>Amount </strong> </label>
                                                <input class="form-control" id="item_total_amount" readonly>
                                                {{-- <h3 id="item_total_amount"></h3> --}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label><strong>Description- </strong> </label>
                                        <textarea name="description" rows="3" class="w-100 ckEditor"></textarea>
                                        <span class="error error_quantity"></span>
                                    </div>
                                </div>
                                <div class="justify-content-center d-flex">
                                    <button type="button" class="btn btn-primary mb-2 p-1 btn-sm" id="addItems"><i
                                            class="fas fa-plus"></i> Add Items</button>
                                </div>

                                {{-- new --}}
                                {{-- <div clas1s="form-group row mt-1" id="item_area">
                                    <button class="btn  btn-danger deletewarrantyButton" style="padding:0 20px" type="button" onclick="this.parentElement.remove()">X</button>
                                    <label><strong>Note :</strong> </label>
                                    <textarea name="description" rows="3" class="w-100 ckEditor"></textarea>
                                    <span class="error error_quantity"></span>

                                </div> --}}

                                {{-- new --}}

                                {{-- <div clas1s="form-group row mt-1" id="terms_condition_area">
                                    <div class="row">
                                        <div class="col-12">
                                            <button class="btn  btn-danger deletewarrantyButton" style="padding:0 20px" type="button" onclick="this.parentElement.parentElement.remove()">X</button>
                                        </div>

                                        <div class="col-12">
                                            <label><strong>Terms and Condition :</strong> </label>
                                            <textarea name="description" rows="3" class="w-100 ckEditor"></textarea>
                                            <span class="error error_quantity"></span>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary btn-sm mb-2 p-1" id="addTermsCondition"><i class="fas fa-plus"></i> Add Sections</button> --}}
                                {{-- new --}}
                                <div class="d-flex justify-content-end">
                                    <div>
                                        <h6>Subtotal <span id="item_total_value">0</span></h6>
                                        <br>
                                        <h6>Total <span>$</span><span id="total_cost"></span></h6>
                                    </div>
                                </div>
                                <div clas1s="form-group row">
                                    <div class="col-xl-3 col-md-6">
                                        <label><strong>Signature </strong> <span class="text-danger">*</span></label>
                                        <input type="file" required name="asset_name" class="form-control add_input"
                                            data-name="Asset Name" id="Asset_name" placeholder="Asset Name" />
                                        <span class="error error_asset_name"></span>
                                    </div>
                                </div>

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

                            </div>

                        </div>
                    </div>
                    <div class="col-3">
                        <h1>This is sidebar</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- Sender --}}
    <div class="modal fade" id="senderModal" tabindex="-1" role="dialog" data-bs-backdrop="static"
        data-bs-keyboard="true" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Edit Business </h6>
                    <strong>Add New Business From Here</strong>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_asset_form" action="" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row mt-1">

                            <div class="col-xl-3 col-md-6">
                                <label><strong>Name </strong> </label>
                                <input type="text" name="model" class="form-control add_input" data-name="Model"
                                    id="model" placeholder="Name" />
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><strong>Website </strong> </label>
                                <input type="url" name="website_2" class="form-control add_input"
                                    data-name="Website_2" id="model" placeholder="URL" />
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><strong>Email </strong></label>
                                <input type="email" name="serial_number" class="form-control add_input"
                                    data-name="serial_number" id="serial_number" placeholder="Email" />
                            </div>


                            <div class="col-xl-3 col-md-6">
                                <label><strong>Mobile </strong></label>
                                <input type="phone" name="serial_number" class="form-control add_input"
                                    data-name="serial_number" id="serial_number" placeholder="Mobile Number" />
                            </div>


                            <div class="col-xl-3 col-md-6">
                                <label><strong>Zip Code </strong></label>
                                <input type="phone" name="serial_number" class="form-control add_input"
                                    data-name="serial_number" id="serial_number" placeholder="Mobile Number" />
                            </div>


                            <div class="col-xl-3 col-md-6">
                                <label><strong>Address </strong></label>
                                <input type="phone" name="serial_number" class="form-control add_input"
                                    data-name="serial_number" id="serial_number" placeholder="Mobile Number" />
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><strong>Logo </strong></label>
                                <input type="file" name="photo" class="form-control add_input" data-name="photo"
                                    id="photo" placeholder="Photo" multiple />
                            </div>


                        </div>

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

    {{-- Receiver --}}
    <div class="modal fade" id="receiverModal" tabindex="-1" role="dialog" data-bs-backdrop="static"
        data-bs-keyboard="true" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Add New Contact </h6>
                    <strong>Add New Contact Here</strong>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_asset_form" action="" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row mt-1">

                            <div class="col-xl-3 col-md-6">
                                <label><strong>Person </strong> </label>
                                <input type="text" name="person" class="form-control add_input" data-name="Person"
                                    id="person" placeholder="Full Name" />
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><strong>Organization </strong> </label>
                                <input type="text" name="organization" class="form-control add_input"
                                    data-name="Organization" id="organization" placeholder="Organization" />
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><strong>email </strong> </label>
                                <input type="email" name="email" class="form-control add_input" data-name="Email"
                                    id="email" placeholder="Email" />
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><strong>Mobile </strong></label>
                                <input type="phone" name="mobile_2" class="form-control add_input" data-name="Mobile"
                                    id="mobile_2" placeholder="Mobile" />
                            </div>


                            <div class="col-xl-3 col-md-6">
                                <label><strong>Website </strong></label>
                                <input type="url" name="website_2" class="form-control add_input"
                                    data-name="Website" id="website_2" placeholder="Website" />
                            </div>


                            <div class="col-xl-3 col-md-6">
                                <label><strong>Country </strong></label>
                                <select required name="counttry" class="form-control submit_able form-select"
                                    id="counttry">
                                    <option class="selected" value="">-- Select Country --</option>
                                    <option value=""></option>
                                </select>
                            </div>


                            <div class="col-xl-3 col-md-6">
                                <label><strong>Region </strong></label>
                                <select required name="depreciation_method" class="form-control submit_able"
                                    id="depreciation_method">
                                    <option class="selected" value="">-- Select Region --</option>
                                </select>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><strong>Address </strong></label>
                                <input type="text" name="address" class="form-control add_input" data-name="Address"
                                    id="address" placeholder="Address" />
                            </div>
                        </div>

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


    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" data-bs-backdrop="static"
        data-bs-keyboard="true" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document" id="edit-content"></div>
    </div>

    <form id="deleted_form" action="" method="post">
        @method('DELETE')
        @csrf
    </form>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js"
        integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>


    <script>
        $('.select2').select2({
            placeholder: "Select a access business location",
            allowClear: true
        });

        var total = 0;
        $('input.item_value').on('input', function() {
            total = 0;
            if (parseInt($('#item_qty').val()) != 0 && parseInt($('#item_rate').val()) != 0) {
                $('input.item_value').each(function() {
                    var amountInfo = parseInt($(this).val());
                    amountInfo = (amountInfo) ? amountInfo : 0;
                    total += amountInfo;
                });
            }
            $('#item_total_value').text(total);
            $('#item_total_amount').text(total);
            $('#total_cost').text(total);
        });


        var section_child = '';
        section_child +=
            '<div><label><strong>@lang('menu.description'):</strong></label><button class="btn btn-danger deletewarrantyButton float-end mb-1" type="button" onclick="this.parentElement.remove()">X</button><textarea name="description" class="w-100 ckEditor"></textarea></div>';

        $('#addSection').on('click', function(e) {
            e.preventDefault();
            $('#section_area').append(section_child);
        })

        var item_child = '';
        item_child += '<div class="col-md-4">';
        item_child += '<label><strong>Title :</strong> </label>';
        item_child +=
            '<input type="text" required name="asset_name" class="form-control add_input" data-name="Asset Name" id="Asset_name" placeholder="Asset Name" />';
        item_child += '<span class="error error_asset_name"></span>';
        item_child += '</div>';
        item_child +=
            '<div class="col-md-8"><div class="row"><div class="col-md-4"><label><strong>Quantity :</strong> </label><div class="input-group"><input type="number" step="any" name="item_qty" class="form-control item_value" data-name="" id="item_qty" placeholder="0" value="0"><select name="balance_type" class="form-control form-select"><option value="">-- Select --</option><option value="1">Pics</option><option value="2">kg</option><option value="3">unit</option></select></div></div><div class="col-md-4"><label><strong>Rate :</strong> </label><input type="number" name="item_rate" class="form-control add_input item_value"  id="item_rate" placeholder="0" value="0"/></div><div class="col-md-4"><label><strong>Amount :</strong> </label><input class="form-control" id="item_total_amount" readonly>{{-- <h3 id="item_total_amount"></h3> --}}</div></div></div>';
        item_child +=
            '<div class="col-md-12"><label><strong>Description- :</strong> </label><textarea name="description" rows="3" class="w-100 ckEditor"></textarea><span class="error error_quantity"></span></div>';
        $('#addItems').on('click', function(e) {
            e.preventDefault();
            $('#item_area').append(item_child);
        })


        var terms_condition = '';
        terms_condition += '<div class="row">';
        terms_condition += '<div class="col-12">';
        terms_condition +=
            '<button class="btn  btn-danger deletewarrantyButton" style="padding:0 20px" type="button" onclick="this.parentElement.parentElement.remove()">X</button>';
        terms_condition += '</div>';
        terms_condition += '<div class="col-12">';
        terms_condition += '<label><strong>Terms and Condition :</strong> </label>';
        terms_condition += '<textarea name="description" rows="3" class="w-100 ckEditor"></textarea>';
        terms_condition += '<span class="error error_quantity"></span>';
        terms_condition += '</div>';
        terms_condition += '</div>';

        $('#addTermsCondition').on('click', function(e) {
            e.preventDefault();
            $('#terms_condition_area').append(terms_condition);
        })

        new Litepicker({
            singleMode: true,
            element: document.getElementById('due_date'),
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
            element: document.getElementById('estimate_date'),
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
    </script>
@endpush
