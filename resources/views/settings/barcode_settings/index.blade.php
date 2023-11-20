@extends('layout.master')
@push('css')

@endpush
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>@lang('menu.barcode_sticker_settings')</h6>
                </div>
                <x-all-buttons>
                    <x-add-button :href="route('settings.barcode.create')" :is_modal="false"/>
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
                            <thead>
                                <tr>
                                    <th class="text-startx">@lang('menu.serial')</th>
                                    <th class="text-startx">@lang('menu.sticker_settings_name')</th>
                                    <th class="text-startx">@lang('menu.sticker_settings_description')</th>
                                    <th class="text-startx">@lang('menu.actions')</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>

                    <form id="deleted_form" action="" method="post">
                        @method('DELETE')
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        var table = $('.data_tbl').DataTable({
            dom: "lBfrtip",
            buttons: [
                {extend: 'pdf',text: '<i class="fa-thin fa-file-pdf fa-2x"></i><br>@lang('menu.pdf')',className: 'pdf btn text-white btn-sm px-1',exportOptions: {columns: 'th:not(:last-child)'}},
                {extend: 'excel',text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>@lang('menu.excel')',className: 'pdf btn text-white btn-sm px-1',exportOptions: {columns: 'th:not(:last-child)'}},
                {extend: 'print',text: '<i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')',className: 'pdf btn text-white btn-sm px-1',exportOptions: {columns: 'th:not(:last-child)'}},
            ],
            processing: true,
            serverSide: true,
            aaSorting: [[3, 'asc']],
            ajax: "{{ route('settings.barcode.index') }}",
            columns: [
                {data: 'DT_RowIndex',name: 'DT_RowIndex'},
                {data: 'name', name: 'name'},
                {data: 'description', name: 'description'},
                {data: 'action', name: 'action'},
            ]
        });
        table.buttons().container().appendTo('#exportButtonsContainer');

        // Setup ajax for csrf token.
        $.ajaxSetup({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        });

        // call jquery method
        $(document).ready(function(){
            // pass editable data to edit modal fields
            $(document).on('click', '#set_default_btn', function(e){
                e.preventDefault();
                $('.data_preloader').show();
                var url = $(this).attr('href');
                $.ajax({
                    url:url,
                    type:'get',
                    success:function(data){
                        table.ajax.reload();
                        toastr.success(data);
                        $('.data_preloader').hide();
                    }
                });
            });

            $(document).on('click', '#delete',function(e){
                e.preventDefault();
                var url = $(this).attr('href');
                $('#deleted_form').attr('action', url);
                $.confirm({
                    'title': 'Delete Confirmation',
                    'content': 'Are you sure?',
                    'buttons': {
                        'Yes': {
                            'class': 'yes btn-danger',
                            'action': function() {$('#deleted_form').submit();}
                        },
                        'No': {
                            'class': 'no btn-primary',
                            'action': function() {
                                // alert('Deleted canceled.');
                            }
                        }
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
                    async:false,
                    data:request,
                    success:function(data){
                        toastr.error(data);
                        table.ajax.reload();
                        $('#deleted_form')[0].reset();
                    }
                });
            });
        });
    </script>
@endpush
