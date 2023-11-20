<div class="modal-dialog four-col-modal" role="document">
    <div class="modal-content" id="server_modal_content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">@lang('menu.cost_centre')</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="select_cost_center_form" action="{{ route('vouchers.journals.cost.centre.prepare') }}" method="POST">
                @csrf
                <div class="row">
                    <input type="hidden" id="cost_allocation_account_index" name="cost_allocation_account_index" value="">
                    <input type="hidden" id="cost_allocation_account_main_group_number" value="">
                    <input type="hidden" id="cost_allocation_amount" value="">
                    <div class="col-xl-12 col-md-12">
                        <table>
                            <tr>
                                <td class="text-end">Cost Allocation For :</td>
                                <td class="fw-bold" id="show_cost_allocation_account"> Lobour Charge</td>
                            </tr>
                            <tr>
                                <td class="text-end">Upto :</td>
                                <td class="fw-bold" id="show_cost_allocation_amount"> 1,000.00 Dr.</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-7 col-md-7">
                        <div class="cost_centre_table_area">
                            <table class="display table table-sm">
                                <thead class="staky">
                                    <tr>
                                        <th class="text-start">Name Of Cost Centre</th>
                                        <th class="text-end">@lang('menu.amount')</th>
                                        <th class="text-end">...</th>
                                    </tr>
                                </thead>

                                <tbody id="cost_centre_table_row_list">
                                    {{-- <tr>
                                        <td>
                                            <div class="row py-1">
                                                <div class="col-12">
                                                    <input type="text" data-only_type="expense" class="form-control fw-bold" id="search_cost_centre" autocomplete="off">
                                                    <input type="hidden" id="cost_centre_name" class="voidable">
                                                    <input type="hidden" id="default_cost_centre_name" name="default_cost_centre_names[]" class="voidable">
                                                    <input type="hidden" name="cost_centre_ids[]" id="cost_centre_id" class="voidable">
                                                    @php
                                                        $costCentreRowUniqueId = uniqid();
                                                    @endphp
                                                    <input type="hidden" class="cost_centre_row_unique_id-{{ $costCentreRowUniqueId }}" id="cost_centre_row_unique_id" value="{{ $costCentreRowUniqueId }}">
                                                </div>
                                            </div>
                                        </td>

                                        <td>
                                            <input type="number" step="any" name="cost_centre_amounts[]" class="form-control fw-bold spinner_hidden text-end" id="cost_centre_amount" value="0.00">
                                        </td>

                                        <td>
                                            <div class="row g-0">
                                                <div class="col-md-6">
                                                    <a href="#" onclick="return false;" tabindex="-1" class="d-inline"><i class="fas fa-trash-alt text-secondary mt-1"></i></a>
                                                </div>

                                                <div class="col-md-6">
                                                    <a href="#" id="add_cost_centre_btn" class="table_tr_add_btn ms-1 d-inline"><i class="fa-solid fa-plus text-success mt-1"></i></a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr> --}}
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <th class="text-center" colspan="1">@lang('menu.total')
                                            : <input type="hidden" id="total_cost_centre_amount" value="">
                                        </th>
                                        <th class="text-end" id="show_total_cost_centre_amount">0.00</th>
                                        <th class="text-end">...</th>
                                    </tr>

                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <div class="col-xl-5 col-md-5">
                        <div class="item-details-sec mb-3 number-fields">
                            <div class="content-inner">
                                <p><strong>List Of Cost Centres</strong></p>
                                <ul class="list-unstyled cost_centre_list" id="cost_centre_list">

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center mt-3">
                    <div class="col-12 d-flex justify-content-end">
                        <div class="loading-btn-box">
                            <button type="button" class="btn loading_button cost_centre_loading_btn display-none"><i class="fas fa-spinner"></i></button>
                            <button type="button" id="cost_center_save_btn" class="btn w-auto btn-success px-3 submit_button">@lang('menu.save')</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
