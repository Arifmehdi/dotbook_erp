<div class="row mt-1 d-lg-flex d-none">
    <div class="col-12">
        <div class="pos-footer">
            <div class="logo_wrapper d-block w-100 text-center">
                <img src="{{asset('images/logo.png')}}"
                    style="max-width: 100%; height: 20px; width: auto;margin-top: 12px;">
            </div>
            @if (json_decode($generalSettings->pos, true)['is_show_recent_transactions'] == '1')
                <div class="pos-foot-con d-inline-block position-absolute" style="right: 10px; top: 10px;">
                    <a href="#" class="resent-tn" tabindex="-1"><span class="fas fa-clock"></span> @lang('menu.recent_transaction')</a>
                </div>
            @endif
        </div>
    </div>
</div>
<div class="sub_total" id="footer_fixed">
    <div class="sub-total-input">
        <div class="row">
            <div class="col-5">
                <div class="row">
                    <label for="inputEmail3" class="col-sm-4 col-form-label text-white">@lang('menu.total_qty') </label>
                    <div class="col-sm-8 ">
                        <input type="text" value="0.00" class="form-control mb_total_qty" disabled>
                    </div>
                </div>
            </div>
            <div class="col-5">
                <div class="row">
                    <label for="inputEmail3" class="col-sm-4 col-form-label text-white">@lang('menu.total_item')</label>
                    <div class="col-sm-8 ">
                        <input type="text" value="0.00" class="form-control mb_total_item" disabled>
                    </div>
                </div>
            </div>

            <div class="col-2 text-center">
                <div class="footer_trasc_btn">
                    @if (json_decode($generalSettings->pos, true)['is_show_recent_transactions'] == '1')
                        <a href="#" class="resent-tn" tabindex="-1"><span class="fas fa-clock"></span></a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{asset('js/SimpleCalculadorajQuery.js')}}" defer></script>
<script>
    $(document).on('click', '.resent-tn',function (e) {
        e.preventDefault();

        showRecentTransectionModal();
    });

    function showRecentTransectionModal() {

        recentSales();
        $('#recentTransModal').modal('show');
        $('.tab_btn').removeClass('tab_active');
        $('#tab_btn').addClass('tab_active');
    }

    function recentSales() {

        $('#recent_trans_preloader').show();
        $.ajax({
            url:"{{url('common/ajax/call/recent/sales/2')}}",
            type:'get',

            success:function(data){

                $('#transection_list').html(data);
                $('#recent_trans_preloader').hide();
            }
        });
    }

    $(document).on('click', '#tab_btn', function(e) {
        e.preventDefault();
        $('#recent_trans_preloader').show();
        var url = $(this).attr('href');

        $.ajax({
            url:url,
            type:'get',
            success:function(data){

                $('#transection_list').html(data);
                $('#recent_trans_preloader').hide();
            }
        });

        $('.tab_btn').removeClass('tab_active');
        $(this).addClass('tab_active');
    });

    $(document).on('click', '#only_print', function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        $.get(url, function(data) {
            $(data).printThis({
                debug: false,
                importCSS: true,
                importStyle: true,
                loadCSS: "{{asset('css/print/sale.print.css')}}",
                removeInline: false,
                printDelay: 500,
                header : null,
                footer : null,
            });
        });
    });
</script>
