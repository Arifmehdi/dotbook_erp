@extends('layout.master')
@push('css')
    <style>
        .top-menu-area ul li {display: inline-block; margin-right: 3px;}
        .top-menu-area a {border: 1px solid lightgray; padding: 1px 5px; border-radius: 3px; font-size: 11px;}

        .custom-btn-group a{
            /* padding: 0; */
            height: 40px;
            width: 220px;
            text-align: center;
            display: flex;
            align-items: center;
            color: black;

        }

        .custom-btn-group a:hover{
            color: #1b1e44;
        }

        .custom-btn-group a i{
            /* border-right: 2px solid black; */
            padding-right: 5px;
        }
    </style>
@endpush
@section('title', 'Sms Settings - ')
@section('content')
<div class="body-wraper">
    <div class="sec-name">
        <h6>Sms Setup & Settings</h6>
        <x-back-button />
    </div>
    <div class="p-15">
        <div class="row">
            <div class="col-12">
                <div class="card custom-mail-ui-card-body">
                    <div class="card-body ">
                        <form id="sms_settings_form" class="setting_form p-3" action="" method="post">
                            @csrf
                                <div class="btn-toolbar pb-2 custom-btn-group" role="toolbar" aria-label="Toolbar with button groups">
                                    <div class="gap-2 me-2 d-flex flex-wrap" role="group" aria-label="First group">
                                        <a href="{{ route('communication.sms.server-setup') }}" class="btn btn-outline-info text-left"><i class="fa-solid fa-database"></i> Add Sms Server</a>
                                        <a href="{{ route('communication.sms.body') }}" class="btn btn-outline-info text-left"><i class="fa-sharp fa-solid fa-envelope-open-text"></i> Sms Body Format</a>
                                        <a href="{{ route('communication.sms.setting') }}" class="btn btn-outline-info text-left"><i class="fa-sharp fa-solid fa-gears"></i> Sms Settings</a>
                                    </div>
                                </div>

                                <div class="btn-toolbar custom-btn-group" role="toolbar" aria-label="Toolbar with button groups">
                                    <div class="gap-2 me-2 d-flex flex-wrap" role="group" aria-label="First group">
                                        <a href="{{ route('communication.sms.manual-service') }}" class="btn btn-outline-info text-left"><i class="fa-solid fa-pen-to-square"></i> Manual Sms</a>
                                        <a href="{{ route('communication.sms.permission') }}" class="btn btn-outline-info text-left"><i class="fa-solid fa-check"></i> Sms Permission</a>
                                        <a href="{{ route('communication.sms.settings') }}" class="btn btn-outline-info text-left"><i class="fa-solid fa-chart-line-up"></i> Sms Report</a>
                                    </div>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@push('scripts')
    <script>
        $('#sms_settings_form').on('submit', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {
                    toastr.success(data);
                    $('.loading_button').hide();
                }
            });
        });
    </script>
@endpush
