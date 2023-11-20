@extends('layout.master')
@push('css')
    <link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.7.0/build/reset-fonts-grids/reset-fonts-grids.css"
        media="all" />

    <style>
        #inner {
            padding: 10px 10px;
            margin-bottom: 0;

        }

        .yui-gf {
            margin-bottom: 2em;
            padding-bottom: 2em;
            border-bottom: 1px solid #ccc;
        }

        #hd {

            margin-top: 10px;
            margin-bottom: 0.5em;
            padding-bottom: 0.5em;
            background-color: #fff;
            /* border-bottom: 1px solid #ccc */
        }

        #hd h2 {
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        #bd,
        #ft {
            margin-bottom: 1em;
        }

        #ft {
            padding: 1em 0 5em 0;
            font-size: 92%;
            text-align: center;
        }

        #ft p {
            margin-bottom: 0;
            text-align: center;
        }

        #hd h1 {
            font-size: 30px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        h2 {
            font-size: 152%
        }

        h3,
        h4 {
            font-size: 100%;
        }

        h1,
        h2,
        h3,
        h4 {
            color: rgb(19, 18, 18);
        }

        p {
            font-size: 100%;
            line-height: 18px;
            padding-right: 3em;
        }

        a {
            color: #990003
        }

        a:hover {
            text-decoration: none;
        }

        strong {
            font-weight: bold;
        }

        /* li { line-height: 24px; border-bottom: 1px solid #ccc; } */
        .contact-info {
            margin-top: 7px;
        }

        .first h4,
        h2 {
            font-style: italic;
            padding-bottom: 10px;
        }

        .last {
            border-bottom: 0
        }

        body {
            text-align: left;
        }

        /* //-- section styles -- */

        a#pdf {
            display: block;
            float: left;
            background: rgb(235, 233, 238);
            color: rgb(23, 2, 56);
            border: 1px solid black;
            border-radius: 5px;
            padding: 6px 5px 6px 6px;
            margin-bottom: 6px;
            text-decoration: none;
        }

        a#pdf:hover {
            background: rgb(16, 2, 95);
            color: rgb(239, 227, 227)
        }


        .last {
            border: none;
        }

        .talent {
            width: 50%;
            float: left;
            padding-bottom: 20px;
        }

        .talent h2 {
            margin-bottom: 6px;
        }

        .talent h4 {
            margin-bottom: 6px;
        }

        .talent p {
            padding-bottom: 10px;
        }


        .yui-gc {
            display: flex;
        }

        .contact-img {
            text-align: right;
            border: 1px dotted green;
            height: 120px;
            width: 90px;
        }

        .contact-img img {
            height: 120px;
            width: 90px;
        }


        #bd,
        #ft {
            margin-bottom: 0;
        }


        #doc2 {
            width: 800px;
            padding: 10px 0;
        }

        .flex_container {
            break-inside: avoid;
            /* margin-top: 30px; */
        }


        .page_brack {
            break-inside: avoid;
        }


        .yui-gc div.first,
        .yui-gd .yui-u {
            width: 42%;
        }

        .alternative_file {
            height: 70px;
            width: 70px;
        }

        .customer_name,
        .customer_add {
            font-style: normal;
            text-align: center;
            padding-top: 20px;

        }

        .customer_add {
            padding-top: 5px;
            font-style: normal !important;
            padding-left: 38px;
            text-align: left;
        }

        #ft {
            padding: 0;
        }

        .printColor {
            font-weight: 700;
            color: rgb(17, 21, 35);
            padding-top: 10px;
            margin-top: 10px;
        }

        label {
            text-align: right;
        }

        .card {
            margin-bottom: 0;
        }

        .all-cards .card {
            border-bottom-width: 0 !important;
            border-radius: 0;
        }

        .all-cards .card:first-child {
            border-radius: 4px 4px 0 0;
        }

        .all-cards .card:last-child {
            border-bottom-width: 1px !important;
            border-radius: 0 0 4px 4px;
        }

        @media print {

            #not_show {
                display: none;
            }

            #doc2 {
                width: 100% !important;
            }

            #inner {
                margin-top: 60px;
                margin-bottom: 0;
            }

            .flex_container {
                break-inside: avoid;
                margin-top: 50px;
            }

            .page_brack {
                /* page-break-after: auto; */

            }

            * {
                font-size: 12px;
                text-align: left;
            }

            .row .col-6 .form-group .row {
                display: flex;
            }

            .printColor {
                color: #000
            }

        }

        @media screen {}
    </style>
@endpush
@section('title', 'supplier View - ')
@section('content')

    <div id="doc2" class="yui-t7">
        <div id="inner" class="card">

            <div class="card-body all-cards">
                <div class="card">
                    <div id="hd" class="card-body">
                        <div class="yui-gc">
                            <div class="yui-u first">
                                <h2 style="color: #000" class="supplier_name">{{ $supplier?->name }}</h2>

                                <a href="mailto:{{ $supplier?->email }}" style="color: #000"
                                    class="supplier_add">{{ $supplier?->email }}</a>
                                <h3 style="color: #000" class="supplier_add">{{ $supplier?->phone }}</h3>
                                <h6 style="color: #000" class="supplier_add">{{ $supplier?->address }}</h6>
                            </div>

                            <div class="yui-u">
                                <div class="contact-info row">
                                    <div class="col-md-12" id="">
                                        <h3 style="color: #000" class="">supplier Details Info (CV)</h3>

                                    </div>

                                    <div class="col-md-12" id="not_show">
                                        <h3>
                                            {{-- <a id="pdf" class="m-1" onclick="printDiv('doc2')" href="#">Print</a>
									<a id="pdf" class="m-1" onclick="generatePDF()" href="#">Download PDF</a> --}}
                                            <a id="pdf" class="m-1" target="_blank"
                                                href="{{ route('contacts.supplier.view.pdf', $supplier->id) }}">PDF
                                                Generate</a>
                                        </h3>
                                    </div>

                                    <div class="col-md-12 pt-2">
                                        {{-- <a href="#" onclick="windowOpen('/../MyPDF.pdf', '_blank', 'fullscreen=yes'); return false;">MyPDF</a> --}}
                                    </div>

                                </div>
                            </div>
                            <div class="yui-u">
                                <div class="contact-img">
                                    @if (@$supplier?->supplierDetails?->supplier_file && $supplier?->supplierDetails?->supplier_file != null)
                                        <img
                                            src="{{ asset('uploads/supplier') . '/' . $supplier?->supplierDetails?->supplier_file }}">
                                        {{-- <img src="{{ asset('uploads/supplier/'.$supplier?->supplierDetails?->supplier_file) }}" alt=""> --}}
                                        {{-- <img src="{{ asset('uploads/supplier') }}/{{ $supplier?->supplierDetails?->supplier_file }}" alt=""> --}}
                                    @else
                                        <img src="{{ asset('images/default.jpg') }}" alt="">
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="bd">
                    <div id="yui-main">
                        <div class="yui-b">

                            <div class="card rounded-0" style="width: 100%;">
                                <div class="card-body printColor pt-1">Company Name & Description</div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group row">
                                                <label class="col-6 col-form-label py-0">Company Name :</label>
                                                <div class="col-6">
                                                    <input type="text" readonly class="form-control-plaintext py-0"
                                                        value="{{ $supplier?->business_name }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <div class="form-group row">
                                                <label class="col-6 col-form-label py-0">Description :</label>
                                                <div class="col-6">
                                                    <textarea readonly class="form-control-plaintext py-0 ckEditor">{{ $supplier?->supplierDetails?->description }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card" style="width: 100%;">
                                <div class="card-body printColor pt-1">Details Information</div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group row">
                                                <label class="col-6 col-form-label py-0">NID No. :</label>
                                                <div class="col-6">
                                                    <input type="text" readonly class="form-control-plaintext py-0"
                                                        value="{{ $supplier?->supplierDetails?->nid_no }}">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-6 col-form-label py-0">Print Name :</label>
                                                <div class="col-6">
                                                    <input type="text" readonly class="form-control-plaintext py-0"
                                                        value="{{ $supplier?->supplierDetails?->print_name }}">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-6 col-form-label py-0">Ledger Name :</label>
                                                <div class="col-6">
                                                    <input type="text" readonly class="form-control-plaintext py-0"
                                                        value="{{ $supplier?->supplierDetails?->print_ledger_name }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <div class="form-group row">
                                                <label class="col-6 col-form-label py-0">Date of Birth :</label>
                                                <div class="col-6">
                                                    <input type="text" readonly class="form-control-plaintext py-0"
                                                        value="{{ $supplier->date_of_birth ? date('Y-M-d', strtotime($supplier->date_of_birth)) : '' }}">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-6 col-form-label py-0">Ledger Code :</label>
                                                <div class="col-6">
                                                    <input type="text" readonly class="form-control-plaintext py-0"
                                                        value="{{ $supplier?->supplierDetails?->print_ledger_code }}">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-6 col-form-label py-0 py-0">Billing Account No :</label>
                                                <div class="col-6">
                                                    <input type="text" readonly class="form-control-plaintext py-0"
                                                        value="{{ $supplier?->supplierDetails?->billing_account }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card" style="width: 100%;">
                                <div class="card-body printColor pt-1">Bank & TAX Info</div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group row">
                                                <label class="col-6 col-form-label py-0">Bank Name :</label>
                                                <div class="col-6">
                                                    <input type="text" readonly class="form-control-plaintext py-0"
                                                        value="{{ $supplier?->supplierDetails?->bank_name }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-6 col-form-label py-0">Bank A/C Number :</label>
                                                <div class="col-6">
                                                    <input type="text" readonly class="form-control-plaintext py-0"
                                                        value="{{ $supplier?->supplierDetails?->bank_A_C_number }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-6 col-form-label py-0">Bank Branch :</label>
                                                <div class="col-6">
                                                    <input type="text" readonly class="form-control-plaintext py-0"
                                                        value="{{ $supplier?->supplierDetails?->bank_branch }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-6 col-form-label py-0">Currency :</label>
                                                <div class="col-6">
                                                    <input type="text" readonly class="form-control-plaintext py-0"
                                                        value="{{ $supplier?->supplierDetails?->bank_currency }}">
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-6">
                                            <div class="form-group row">
                                                <label class="col-6 col-form-label py-0">TIN No :</label>
                                                <div class="col-6">
                                                    <input type="text" readonly class="form-control-plaintext py-0"
                                                        value="{{ $supplier?->supplierDetails?->tax_number }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-6 col-form-label py-0">TAX No :</label>
                                                <div class="col-6">
                                                    <input type="text" readonly class="form-control-plaintext py-0"
                                                        value="{{ $supplier?->supplierDetails?->tin_number }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-6 col-form-label py-0">Name :</label>
                                                <div class="col-6">
                                                    <input type="text" readonly class="form-control-plaintext py-0"
                                                        value="{{ $supplier?->supplierDetails?->tax_name }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-6 col-form-label py-0">Category :</label>
                                                <div class="col-6">
                                                    <input type="text" readonly class="form-control-plaintext py-0"
                                                        value="{{ $supplier?->supplierDetails?->tax_category }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-6 col-form-label py-0">Address :</label>
                                                <div class="col-6">
                                                    <input type="text" readonly class="form-control-plaintext py-0"
                                                        value="{{ $supplier?->supplierDetails?->tax_address }}">
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card" style="width: 100%;">
                                <div class="card-body printColor pt-1">Primary Contact Info & Address</div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group row">
                                                <label class="col-6 col-form-label py-0">Mailing Name :</label>
                                                <div class="col-6">
                                                    <input type="text" readonly class="form-control-plaintext py-0"
                                                        value="{{ $supplier?->supplierDetails?->contact_mailing_name }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-6 col-form-label py-0">Mobile :</label>
                                                <div class="col-6">
                                                    <input type="text" readonly class="form-control-plaintext py-0"
                                                        value="{{ $supplier?->supplierDetails?->primary_mobile }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-6 col-form-label py-0">Email :</label>
                                                <div class="col-6">
                                                    <input type="text" readonly class="form-control-plaintext py-0"
                                                        value="{{ $supplier?->supplierDetails?->contact_email }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-6 col-form-label py-0">Telephone No :</label>
                                                <div class="col-6">
                                                    <input type="text" readonly class="form-control-plaintext py-0"
                                                        value="{{ $supplier?->supplierDetails?->contact_telephone }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-6 col-form-label py-0">Fax No :</label>
                                                <div class="col-6">
                                                    <input type="text" readonly class="form-control-plaintext py-0"
                                                        value="{{ $supplier?->supplierDetails?->contact_fax }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-6 col-form-label py-0">Present :</label>
                                                <div class="col-6">
                                                    <input type="text" readonly class="form-control-plaintext py-0"
                                                        value="{{ $supplier?->address }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-6 col-form-label py-0">Permanent :</label>
                                                <div class="col-6">
                                                    <input type="text" readonly class="form-control-plaintext py-0"
                                                        value="{{ $supplier?->supplierDetails?->permanent_address }}">
                                                </div>
                                            </div>


                                        </div>



                                        <div class="col-6">
                                            <div class="form-group row">
                                                <label class="col-6 col-form-label py-0">Post Office :</label>
                                                <div class="col-6">
                                                    <input type="text" readonly class="form-control-plaintext py-0"
                                                        value="{{ $supplier?->supplierDetails?->contact_post_office }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-6 col-form-label py-0">Post Code :</label>
                                                <div class="col-6">
                                                    <input type="text" readonly class="form-control-plaintext py-0"
                                                        value="{{ $supplier?->zip_code }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-6 col-form-label py-0">Police Station :</label>
                                                <div class="col-6">
                                                    <input type="text" readonly class="form-control-plaintext py-0"
                                                        value="{{ $supplier?->supplierDetails?->contact_police_station }}">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-6 col-form-label py-0"> State :</label>
                                                <div class="col-6">
                                                    <input type="text" readonly class="form-control-plaintext py-0"
                                                        value="{{ $supplier?->state }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-6 col-form-label py-0"> city :</label>
                                                <div class="col-6">
                                                    <input type="text" readonly class="form-control-plaintext py-0"
                                                        value="{{ $supplier?->city }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-6 col-form-label py-0">Country :</label>
                                                <div class="col-6">
                                                    <input type="text" readonly class="form-control-plaintext py-0"
                                                        value="{{ $supplier?->country }}">
                                                </div>
                                            </div>



                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="card flex_container paddingAsPdf" style="width: 100%;">
                                <div class="card-body printColor pt-1">Mailing & Shipping Info</div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group row">
                                                <label class="col-6 col-form-label py-0">Mailing Name</label>
                                                <div class="col-6">
                                                    <input type="text" readonly class="form-control-plaintext py-0"
                                                        value="{{ $supplier?->supplierDetails?->mailing_name }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-6 col-form-label py-0">Address</label>
                                                <div class="col-6">
                                                    <input type="text" readonly class="form-control-plaintext py-0"
                                                        value="{{ $supplier?->supplierDetails?->mailing_address }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-6 col-form-label py-0">Mail Address</label>
                                                <div class="col-6">
                                                    <input type="text" readonly class="form-control-plaintext py-0"
                                                        value="{{ $supplier?->supplierDetails?->mailing_email }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <div class="form-group row">
                                                <label class="col-6 col-form-label py-0">Shipping Name</label>
                                                <div class="col-6">
                                                    <input type="text" readonly class="form-control-plaintext py-0"
                                                        value="{{ $supplier?->supplierDetails?->shipping_name }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-6 col-form-label py-0">Address</label>
                                                <div class="col-6">
                                                    <input type="text" readonly class="form-control-plaintext py-0"
                                                        value="{{ $supplier?->shipping_address }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-6 col-form-label py-0">Mobile No</label>
                                                <div class="col-6">
                                                    <input type="text" readonly class="form-control-plaintext py-0"
                                                        value="{{ $supplier?->supplierDetails?->shipping_number }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-6 col-form-label py-0">Mail Address</label>
                                                <div class="col-6">
                                                    <input type="text" readonly class="form-control-plaintext py-0"
                                                        value="{{ $supplier?->supplierDetails?->shipping_email }}">
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>

                            {{-- <div class="card flex_container" style="width: 100%;">
							<div class="card-body printColor pt-1">Partner & Commissions</div>
							<div class="card-body">
								<div class="row">
									<div class="col-6">

										<h5 class="card-title text-left pb-4">Sales Partner</h5>

										<div class="form-group row">
											<label  class="col-6 col-form-label py-0">Partner Name :</label>
											<div class="col-6">
											<input type="text" readonly class="form-control-plaintext py-0" value="{{$supplier?->supplierDetails?->partner_name}}">
											</div>
										</div>
									</div>

									<div class="col-6">

										<h5 class="card-title text-left pb-4">Partner Commissions</h5>

										<div class="form-group row">
											<label class="col-6 col-form-label py-0">Percentes (%)</label>
											<div class="col-6">
											<input type="text" readonly class="form-control-plaintext py-0" value="{{$supplier?->supplierDetails?->percentes}}">
											</div>
										</div>
									</div>

								</div>
							</div>
						</div> --}}

                            <div class="card flex_container" style="width: 100%;">
                                <div class="card-body printColor pt-1">Alternative Contact Info</div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group row">
                                                <label class="col-6 col-form-label py-0">Name :</label>
                                                <div class="col-6">
                                                    <input type="text" readonly class="form-control-plaintext py-0"
                                                        value="{{ $supplier?->supplierDetails?->alternative_name }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-6 col-form-label py-0">Mobile :</label>
                                                <div class="col-6">
                                                    <input type="text" readonly class="form-control-plaintext py-0"
                                                        value="{{ $supplier?->known_person_phone }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-6 col-form-label py-0">Email :</label>
                                                <div class="col-6">
                                                    <input type="text" readonly class="form-control-plaintext py-0"
                                                        value="{{ $supplier?->supplierDetails?->alternative_email }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-6 col-form-label py-0">Fax :</label>
                                                <div class="col-6">
                                                    <input type="text" readonly class="form-control-plaintext py-0"
                                                        value="{{ $supplier?->supplierDetails?->alternative_fax }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-6 col-form-label py-0">Telephone No :</label>
                                                <div class="col-6">
                                                    <input type="text" readonly class="form-control-plaintext py-0"
                                                        value="{{ $supplier?->landline }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-6 col-form-label py-0">Image :</label>
                                                <div class="col-6">

                                                    @if (@$supplier?->supplierDetails?->alternative_file && $supplier?->supplierDetails?->alternative_file != null)
                                                        <img class="alternative_file"
                                                            src="{{ asset('uploads/supplier/alternative/' . $supplier?->supplierDetails?->alternative_file) }}"
                                                            alt="">
                                                    @else
                                                        <img src="{{ asset('images/default.jpg') }}" alt="">
                                                    @endif
                                                </div>
                                            </div>

                                        </div>



                                        <div class="col-6">
                                            <div class="form-group row">
                                                <label class="col-6 col-form-label py-0">Address :</label>
                                                <div class="col-6">
                                                    <input type="text" readonly class="form-control-plaintext py-0"
                                                        value="{{ $supplier?->supplierDetails?->alternative_address }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-6 col-form-label py-0">Post Office :</label>
                                                <div class="col-6">
                                                    <input type="text" readonly class="form-control-plaintext py-0"
                                                        value="{{ $supplier?->supplierDetails?->alternative_post_office }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-6 col-form-label py-0">Post Code :</label>
                                                <div class="col-6">
                                                    <input type="text" readonly class="form-control-plaintext py-0"
                                                        value="{{ $supplier?->supplierDetails?->alternative_zip_code }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-6 col-form-label py-0">Police Station :</label>
                                                <div class="col-6">
                                                    <input type="text" readonly class="form-control-plaintext py-0"
                                                        value="{{ $supplier?->supplierDetails?->alternative_police_station }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-6 col-form-label py-0">State :</label>
                                                <div class="col-6">
                                                    <input type="text" readonly class="form-control-plaintext py-0"
                                                        value="{{ $supplier?->supplierDetails?->alternative_state }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-6 col-form-label py-0">City :</label>
                                                <div class="col-6">
                                                    <input type="text" readonly class="form-control-plaintext py-0"
                                                        value="{{ $supplier?->supplierDetails?->alternative_city }}">
                                                </div>
                                            </div>


                                        </div>



                                    </div>
                                </div>
                            </div>

                            @if (count($supplier?->supplierContactPersonDetails) > 0)
                                <div class="card" style="width: 100%;">
                                    @foreach ($supplier?->supplierContactPersonDetails as $k => $contact_person)
                                        @if ($k + 1 == 2 || $k + 1 == 5 || $k + 1 == 9)
                                            <div class="card-body printColor flex_container">Contact Parsones
                                                {{ $k + 1 }}</div>
                                            <div id="" class="card-body border page_brack">
                                                <div class="row ">
                                                    <div class="col-6">

                                                        <div class="form-group row">
                                                            <label class="col-6 col-form-label py-0">Name :</label>
                                                            <div class="col-6">
                                                                <input type="text" readonly
                                                                    class="form-control-plaintext py-0"
                                                                    value="{{ $contact_person->contact_person_name }}">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-6 col-form-label py-0">Phone :</label>
                                                            <div class="col-6">
                                                                <input type="text" readonly
                                                                    class="form-control-plaintext py-0"
                                                                    value="{{ $contact_person->contact_person_phon }}">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-6 col-form-label py-0">Alternative Phone
                                                                :</label>
                                                            <div class="col-6">
                                                                <input type="text" readonly
                                                                    class="form-control-plaintext py-0"
                                                                    value="{{ $contact_person->contact_person_alternative_phone }}">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-6 col-form-label py-0">Telephone No :</label>
                                                            <div class="col-6">
                                                                <input type="text" readonly
                                                                    class="form-control-plaintext py-0"
                                                                    value="{{ $contact_person->contact_person_landline }}">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-6 col-form-label py-0">Post Office :</label>
                                                            <div class="col-6">
                                                                <input type="text" readonly
                                                                    class="form-control-plaintext py-0"
                                                                    value="{{ $contact_person->contact_person_post_office }}">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-6 col-form-label py-0">Police Station
                                                                :</label>
                                                            <div class="col-6">
                                                                <input type="text" readonly
                                                                    class="form-control-plaintext py-0"
                                                                    value="{{ $contact_person->contact_person_police_station }}">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-6 col-form-label py-0">City :</label>
                                                            <div class="col-6">
                                                                <input type="text" readonly
                                                                    class="form-control-plaintext py-0"
                                                                    value="{{ $contact_person->contact_person_city }}">
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <div class="col-6">

                                                        <div class="form-group row">
                                                            <label class="col-6 col-form-label py-0">Designation :</label>
                                                            <div class="col-6">
                                                                <input type="text" readonly
                                                                    class="form-control-plaintext py-0"
                                                                    value="{{ $contact_person->contact_person_dasignation }}">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-6 col-form-label py-0">Email :</label>
                                                            <div class="col-6">
                                                                <input type="text" readonly
                                                                    class="form-control-plaintext py-0"
                                                                    value="{{ $contact_person->contact_person_email }}">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-6 col-form-label py-0">Fax :</label>
                                                            <div class="col-6">
                                                                <input type="text" readonly
                                                                    class="form-control-plaintext py-0"
                                                                    value="{{ $contact_person->contact_person_alternative_phone }}">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-6 col-form-label py-0">Address :</label>
                                                            <div class="col-6">
                                                                <input type="text" readonly
                                                                    class="form-control-plaintext py-0"
                                                                    value="{{ $contact_person->contact_person_address }}">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-6 col-form-label py-0">Post Code :</label>
                                                            <div class="col-6">
                                                                <input type="text" readonly
                                                                    class="form-control-plaintext py-0"
                                                                    value="{{ $contact_person->contact_person_zip_code }}">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-6 col-form-label py-0">State :</label>
                                                            <div class="col-6">
                                                                <input type="text" readonly
                                                                    class="form-control-plaintext py-0"
                                                                    value="{{ $contact_person->contact_person_state }}">
                                                            </div>
                                                        </div>

                                                    </div>

                                                </div>

                                            </div>
                                        @else
                                            <div class="card-body printColor pt-1 flex_container">Contact Parsones
                                                {{ $k + 1 }}</div>
                                            <div id="" class="card-body border page_brack">
                                                <div class="row ">
                                                    <div class="col-6">

                                                        <div class="form-group row">
                                                            <label class="col-6 col-form-label py-0">Name :</label>
                                                            <div class="col-6">
                                                                <input type="text" readonly
                                                                    class="form-control-plaintext py-0"
                                                                    value="{{ $contact_person->contact_person_name }}">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-6 col-form-label py-0">Phone :</label>
                                                            <div class="col-6">
                                                                <input type="text" readonly
                                                                    class="form-control-plaintext py-0"
                                                                    value="{{ $contact_person->contact_person_phon }}">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-6 col-form-label py-0">Alternative Phone
                                                                :</label>
                                                            <div class="col-6">
                                                                <input type="text" readonly
                                                                    class="form-control-plaintext py-0"
                                                                    value="{{ $contact_person->contact_person_alternative_phone }}">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-6 col-form-label py-0">Telephone No :</label>
                                                            <div class="col-6">
                                                                <input type="text" readonly
                                                                    class="form-control-plaintext py-0"
                                                                    value="{{ $contact_person->contact_person_landline }}">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-6 col-form-label py-0">Post Office :</label>
                                                            <div class="col-6">
                                                                <input type="text" readonly
                                                                    class="form-control-plaintext py-0"
                                                                    value="{{ $contact_person->contact_person_post_office }}">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-6 col-form-label py-0">Police Station
                                                                :</label>
                                                            <div class="col-6">
                                                                <input type="text" readonly
                                                                    class="form-control-plaintext py-0"
                                                                    value="{{ $contact_person->contact_person_police_station }}">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-6 col-form-label py-0">City :</label>
                                                            <div class="col-6">
                                                                <input type="text" readonly
                                                                    class="form-control-plaintext py-0"
                                                                    value="{{ $contact_person->contact_person_city }}">
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <div class="col-6">

                                                        <div class="form-group row">
                                                            <label class="col-6 col-form-label py-0">Designation :</label>
                                                            <div class="col-6">
                                                                <input type="text" readonly
                                                                    class="form-control-plaintext py-0"
                                                                    value="{{ $contact_person->contact_person_dasignation }}">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-6 col-form-label py-0">Email :</label>
                                                            <div class="col-6">
                                                                <input type="text" readonly
                                                                    class="form-control-plaintext py-0"
                                                                    value="{{ $contact_person->contact_person_email }}">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-6 col-form-label py-0">Fax :</label>
                                                            <div class="col-6">
                                                                <input type="text" readonly
                                                                    class="form-control-plaintext py-0"
                                                                    value="{{ $contact_person->contact_person_alternative_phone }}">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-6 col-form-label py-0">Address :</label>
                                                            <div class="col-6">
                                                                <input type="text" readonly
                                                                    class="form-control-plaintext py-0"
                                                                    value="{{ $contact_person->contact_person_address }}">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-6 col-form-label py-0">Post Code :</label>
                                                            <div class="col-6">
                                                                <input type="text" readonly
                                                                    class="form-control-plaintext py-0"
                                                                    value="{{ $contact_person->contact_person_zip_code }}">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-6 col-form-label py-0">State :</label>
                                                            <div class="col-6">
                                                                <input type="text" readonly
                                                                    class="form-control-plaintext py-0"
                                                                    value="{{ $contact_person->contact_person_state }}">
                                                            </div>
                                                        </div>

                                                    </div>

                                                </div>

                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endif



                        </div>
                    </div>
                </div>
            </div>

            <div id="ft">
                <p>{{ $supplier?->business_name }} &mdash; <a
                        href="mailto::{{ $supplier?->email }}">{{ $supplier?->email }}</a> &mdash;<a
                        href="callto::{{ $supplier?->phone }}">{{ $supplier?->phone }}</a> </p>
            </div>

        </div>

    </div>


@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        function generatePDF() {

            $(".pdfMargin").css("padding-top", 250);
            $(".paddingAsPdf").css("padding-top", 20);
            // $("#myTable").find('tr:nth-child(3n+1)').css('background', 'black');
            $("#not_show").css("display", "none");

            const element = document.getElementById('doc2');
            var opt = {
                margin: 2,
                filename: 'erp_supplier_info.pdf',
                //   image:        { type: 'jpg', quality: 0.09 },
                html2canvas: {
                    scale: 2
                },
                jsPDF: {
                    // pagebreak: {mode: 'avoid-all', before:'#pageX'}
                    orientation: 'p',
                    unit: 'mm',
                    format: 'a4',
                    putOnlyUsedFonts: true,
                }
            };

            html2pdf().set(opt).from(element).save();

            // openPDF('erp_supplier_info.pdf');
        }

        function openPDF(pdf) {
            window.open(pdf);
            return false;
        }
    </script>


    <script>
        $(document).ready(function() {
            // window.printPreview();
        });

        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;

            window.print();
            // window.printPreview();
            document.body.innerHTML = originalContents;
        }
    </script>
@endpush
