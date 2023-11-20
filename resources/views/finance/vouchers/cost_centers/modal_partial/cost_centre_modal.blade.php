<div class="modal fade" id="costCentreModal" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog four-col-modal" role="document">
        <div class="modal-content">
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
                        <input type="hidden" name="cost_allocation_amount" id="cost_allocation_amount" value="">
                        <div class="col-xl-12 col-md-12">
                            <table>
                                <tr>
                                    <td class="text-end">@lang('menu.cost_allocation_for') :</td>
                                    <td class="fw-bold" id="show_cost_allocation_account"> Lobour Charge</td>
                                </tr>
                                <tr>
                                    <td class="text-end">@lang('menu.upto') :</td>
                                    <td class="fw-bold" id="show_cost_allocation_amount"> 1,000.00 Dr.</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-7 col-md-7">
                            <div class="cost_centre_table_area">
                                <div class="table-responsive">
                                    <table class="display data__table table-striped">
                                        <thead class="staky">
                                            <tr>
                                                <th class="text-start">@lang('menu.name_of_cost_centre')</th>
                                                <th class="text-end">@lang('menu.amount')</th>
                                                <th class="text-end">...</th>
                                            </tr>
                                        </thead>

                                        <tbody id="cost_centre_table_row_list"></tbody>

                                        <tfoot>
                                            <tr>
                                                <th class="text-center" colspan="1">@lang('menu.total')
                                                    : <input type="hidden" name="total_cost_centre_amount" id="total_cost_centre_amount" value="">
                                                </th>
                                                <th class="text-end" id="show_total_cost_centre_amount">0.00</th>
                                                <th class="text-end">...</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-5 col-md-5">
                            <div class="item-details-sec mb-3 number-fields">
                                <div class="content-inner">
                                    <p><strong>@lang('menu.list_of_cost_centres')</strong></p>
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
                                <button type="button" id="cost_center_save_btn" class="btn w-auto btn-success px-3 cost_center_submit_button">@lang('menu.save')</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
