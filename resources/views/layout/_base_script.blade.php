
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="{{ asset('js/script.js') }}"></script>
<script src="{{asset('js/number-bdt-formater.js')}}"></script>
<script src="{{ asset('plugins/bootstrap-bundle.min.js') }}"></script>
<script src="{{ asset('plugins/print_this/printThis.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
<script src="{{ asset('js/toastrjs/toastr.min.js') }}"></script>
<script  src="{{asset('plugins/data-table/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('js/bootstrap-dropdown.js')}}"></script>
<script src="{{asset('js/TableTools.min.js')}}"></script>
<script src="{{asset('js/jeditable.jquery.js')}}"></script>
<script src="{{asset('js/custom-script.js')}}"></script>
<script src="{{asset('js/main.js')}}"></script>
<script src="{{asset('js/SimpleCalculadorajQuery.js')}}" defer></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    toastr.options = {"positionClass": "toast-top-center"}

    $(document).on('click', '#logout_option', function(e) {
        e.preventDefault();
        $.confirm({
            'title': 'Logout Confirmation'
            , 'content': 'Are you sure, you want to logout?'
            , 'buttons': {
                'Yes': {
                    'btnClass': 'yes btn-primary'
                    , 'action': function() {
                        $('#logout_form').submit();
                    }
                }
                , 'No': {
                    'btnClass': 'no btn-danger'
                    , 'action': function() {

                    }
                }
            }
        });
    });

    $(document).on('click', '.display tbody tr', function() {
        $('.display tbody tr').removeClass('active_tr');
        $(this).addClass('active_tr');
    });

    $(document).on('click', '#hard_reload', function() {
        window.location.reload(true);
    });
</script>
