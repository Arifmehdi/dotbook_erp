@extends('layout.master')
@push('css')
@endpush
@section('title', 'Role List - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>@lang('menu.roles')</h6>
                </div>
                <x-all-buttons>
                    <x-add-button :href="route('users.role.create')" :can="'role_add'" :is_modal="false" />
                    <x-slot name="after">
                        <x-help-button />
                    </x-slot>
                </x-all-buttons>
            </div>
        </div>

        <div class="p-15">
            <div class="form_element rounded m-0">
                <div class="element-body">
                    <div class="table-responsive h-350" id="data-list">
                        <table class="display data_tbl data__table">
                        </table>
                    </div>
                </div>

                <form id="deleted_form" action="" method="post">
                    @method('DELETE')
                    @csrf
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script>
    @if (Session::has('successMsg'))
        toastr.success('{{ session('successMsg') }}');
    @endif

    function getAllRoles(){
        $('.data_preloader').show();
        $.ajax({
            url:"{{ route('users.role.all.roles') }}",
            type:'get',
            success:function(data){
                $('.table-responsive').html(data);
                $('.data_preloader').hide();
            }
        });
    }
    getAllRoles();

    // Setup ajax for csrf token.
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

    // call jquery method
    $(document).ready(function(){
        $(document).on('click', '#delete',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            $('#deleted_form').attr('action', url);
            $.confirm({
                'title': 'Delete Confirmation',
                'content': 'Are you sure?',
                'buttons': {
                    'Yes': {'class': 'yes btn-danger','action': function() {$('#deleted_form').submit();}}
                }
            });
        });

        //data delete by ajax
        $(document).on('submit', '#deleted_form',function(e){
            e.preventDefault();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url:url,
                type:'post',
                data:request,
                success:function(data){
                    getAllRoles();
                    toastr.error(data);
                    $('#deleted_form')[0].reset();
                },
                error: function(data){
                    toastr.error(data.responseJSON.message);
                }
            });
        });
    });
</script>
@endpush
