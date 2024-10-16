@extends('layout.master')
@push('css')
    <link href="{{ asset('css/tab.min.css') }}" rel="stylesheet" type="text/css" />
@endpush
@section('title', 'All Categories/SubCategories - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>Contacts</h6>
                </div>
                <x-back-button />
            </div>
        </div>

        <div class="p-15">
            <div class="form_element rounded m-0">
                <div class="element-body">
                    <div class="name-head">
                        <div class="tab_list_area">
                            <ul class="list-unstyled">
                                <li>
                                    <a id="tab_btn" data-show="contact_group_list" class="tab_btn tab_active"
                                        href="#">
                                        Contact List</a>
                                </li>
                                <li>
                                    <a id="tab_btn" data-show="conatct_group" class="tab_btn" href="#">
                                        Contact Group</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    @include('communication::contacts.type.bodyPartial.index')
                    @include('communication::contacts.list.bodyPartial.index')
                </div>
            </div>
        </div>
    </div>

    <form id="deleted_number_form" action="" method="post">
        @method('DELETE')
        @csrf
    </form>
    <form id="deleted_group_form" action="" method="post">
        @method('DELETE')
        @csrf
    </form>

@endsection
@push('scripts')
    @include('communication::contacts.type.contact_type_script')
    @include('communication::contacts.list.contact_list_script')
    <script>
        $('.conatct_group').hide();
        $(document).on('click', '#tab_btn', function(e) {
            e.preventDefault();
            $('.tab_btn').removeClass('tab_active');
            $('.tab_contant').addClass('d-none');
            var show_content = $(this).data('show');
            $('.' + show_content).removeClass('d-none');
            $(this).addClass('tab_active');
        });
    </script>
@endpush
