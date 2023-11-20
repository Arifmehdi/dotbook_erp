@extends('layout.employee-master')
@push('css')

@endpush
@section('content')
<div class="body-wraper">
    <div class="container-fluid p-0">
        <div class="sec-name user-profile-header">
            <div class="logo">
                <img src="{{ asset('images/logo.png') }}" alt="Image">
            </div>
            <div class="header-btn">
                <nav>
                    <div class="nav nav-tabs border-0" id="nav-tab" role="tablist">
                        <button class="nav-link active" id="nav-dashboard-tab" data-bs-toggle="tab" data-bs-target="#nav-dashboard" type="button" role="tab" aria-controls="nav-dashboard" aria-selected="true">
                            <span class="icon"><i class="fa-light fa-home"></i></span>
                            <span>Dashboard</span>
                        </button>
                        <button class="nav-link" id="nav-leaves-tab" data-bs-toggle="tab" data-bs-target="#nav-leaves" type="button" role="tab" aria-controls="nav-leaves" aria-selected="false">
                            <span class="icon"><i class="fa-light fa-calendar"></i></span>
                            <span>Leaves</span>
                        </button>
                        <div class="dropdown">
                            <button class="nav-link" data-bs-toggle="dropdown">
                                <span class="icon"><i class="fa-light fa-money-bill"></i></span>
                                <span>Self</span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="#" class="dropdown-item">Salary Slips</a></li>
                                <li><a href="#" class="dropdown-item">Expense Claims</a></li>
                            </ul>
                        </div>
                        <button class="nav-link" id="nav-attendance-tab" data-bs-toggle="tab" data-bs-target="#nav-attendance" type="button" role="tab" aria-controls="nav-attendance" aria-selected="false">
                            <span class="icon"><i class="fa-light fa-calendar-check"></i></span>
                            <span>Attendance</span>
                        </button>
                        <button class="nav-link" id="nav-settings-tab" data-bs-toggle="tab" data-bs-target="#nav-settings" type="button" role="tab" aria-controls="nav-settings" aria-selected="false">
                            <span class="icon"><i class="fa-light fa-gear"></i></span>
                            <span>settings</span>
                        </button>
                        <button class="nav-link" id="log_out" type="button">
                            <span class="icon"><i class="fa-light fa-power-off"></i></span>
                            <span>Log Out</span>
                        </button>
                    </div>
                </nav>
            </div>
        </div>
        <form id="add_user_form" class="mt-2" action="{{ route('users.store') }}" enctype="multipart/form-data" method="POST">
            @csrf
            <div class="row g-1">
                <div class="col-xxl-3 col-xl-4 col-md-5">
                    <div class="card mb-1">
                        <div class="card-body p-2">
                            <div class="profile-sidebar">
                                <div class="profile-top">
                                    <div class="part-img">
                                        <img src="{{ asset('images/profile-picture.jpg') }}" alt="Image">
                                    </div>
                                    <div class="part-txt text-center">
                                        <h4>{!! $user->username ? $user->username : '<span class="badge bg-secondary">Not-Allowed-to-Login</span>' !!}</h4>
                                    </div>
                                </div>
                                <ul class="profile-short-info">
                                    <li>Employee ID<span>9002</span></li>
                                    {{-- <li>@lang('menu.role')<span>{{ $user?->roles()?->first()?->name }}</span></li> --}}
                                    <li>Designation<span>Faculty</span></li>
                                    <li>@lang('menu.departments')<span>Academic</span></li>
                                    <li>Employee Type<span>Permanent</span></li>
                                    <li>Shift<span>Morning</span></li>
                                    <li>Joining Date<span>03/10/2022</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-1 bg-primary text-white text-center py-2 px-4">
                        <p><span class="fw-medium">Service Period :</span> 1y, 10m, 10d</p>
                    </div>
                    <div class="card mb-1 py-3 profile-stat">
                        <x-table-stat :items="[
                            ['id' => 'supplier', 'name' => __('Attendance'), 'value' => 123],
                            ['id' => 'active_supplier', 'name' => __('Leaves'), 'value' => 321],
                            ['id' => 'inactive_supplier', 'name' => __('Awards'), 'value' => 958]
                        ]" />
                    </div>
                    <div class="card mb-0">
                        <div class="card-header px-3 bg-secondary text-white">
                            <h5 class="card-title mt-0">Notice Board</h5>
                        </div>
                        <div class="card-body p-2">
                            <div class="notice-board">
                                <div class="single-notice">
                                    <div class="date-box">
                                        <span>{{ date('d')}}</span>
                                        <span>{{ date('m, Y') }}</span>
                                    </div>
                                    <div class="part-txt">
                                        <a role="button" class="notice-title" data-bs-toggle="modal" data-bs-target="#noticeModal">AS SHE SAID TO HERSELF, RATHER SHARPLY; 'I.</a>
                                        <p>Alice began to repeat it, but her head in the sky. Alice went on without attending to her; 'but.</p>
                                    </div>
                                </div>
                                <div class="single-notice">
                                    <div class="date-box">
                                        <span>{{ date('d')}}</span>
                                        <span>{{ date('m, Y') }}</span>
                                    </div>
                                    <div class="part-txt">
                                        <a role="button" class="notice-title" data-bs-toggle="modal" data-bs-target="#noticeModal">AS SHE SAID TO HERSELF, RATHER SHARPLY; 'I.</a>
                                        <p>Alice began to repeat it, but her head in the sky. Alice went on without attending to her; 'but.</p>
                                    </div>
                                </div>
                                <div class="single-notice">
                                    <div class="date-box">
                                        <span>{{ date('d')}}</span>
                                        <span>{{ date('m, Y') }}</span>
                                    </div>
                                    <div class="part-txt">
                                        <a role="button" class="notice-title" data-bs-toggle="modal" data-bs-target="#noticeModal">AS SHE SAID TO HERSELF, RATHER SHARPLY; 'I.</a>
                                        <p>Alice began to repeat it, but her head in the sky. Alice went on without attending to her; 'but.</p>
                                    </div>
                                </div>
                                <div class="single-notice">
                                    <div class="date-box">
                                        <span>{{ date('d')}}</span>
                                        <span>{{ date('m, Y') }}</span>
                                    </div>
                                    <div class="part-txt">
                                        <a role="button" class="notice-title" data-bs-toggle="modal" data-bs-target="#noticeModal">AS SHE SAID TO HERSELF, RATHER SHARPLY; 'I.</a>
                                        <p>Alice began to repeat it, but her head in the sky. Alice went on without attending to her; 'but.</p>
                                    </div>
                                </div>
                                <div class="single-notice">
                                    <div class="date-box">
                                        <span>{{ date('d')}}</span>
                                        <span>{{ date('m, Y') }}</span>
                                    </div>
                                    <div class="part-txt">
                                        <a role="button" class="notice-title" data-bs-toggle="modal" data-bs-target="#noticeModal">AS SHE SAID TO HERSELF, RATHER SHARPLY; 'I.</a>
                                        <p>Alice began to repeat it, but her head in the sky. Alice went on without attending to her; 'but.</p>
                                    </div>
                                </div>
                                <div class="single-notice">
                                    <div class="date-box">
                                        <span>{{ date('d')}}</span>
                                        <span>{{ date('m, Y') }}</span>
                                    </div>
                                    <div class="part-txt">
                                        <a role="button" class="notice-title" data-bs-toggle="modal" data-bs-target="#noticeModal">AS SHE SAID TO HERSELF, RATHER SHARPLY; 'I.</a>
                                        <p>Alice began to repeat it, but her head in the sky. Alice went on without attending to her; 'but.</p>
                                    </div>
                                </div>
                                <div class="single-notice">
                                    <div class="date-box">
                                        <span>{{ date('d')}}</span>
                                        <span>{{ date('m, Y') }}</span>
                                    </div>
                                    <div class="part-txt">
                                        <a role="button" class="notice-title" data-bs-toggle="modal" data-bs-target="#noticeModal">AS SHE SAID TO HERSELF, RATHER SHARPLY; 'I.</a>
                                        <p>Alice began to repeat it, but her head in the sky. Alice went on without attending to her; 'but.</p>
                                    </div>
                                </div>
                                <div class="single-notice">
                                    <div class="date-box">
                                        <span>{{ date('d')}}</span>
                                        <span>{{ date('m, Y') }}</span>
                                    </div>
                                    <div class="part-txt">
                                        <a role="button" class="notice-title" data-bs-toggle="modal" data-bs-target="#noticeModal">AS SHE SAID TO HERSELF, RATHER SHARPLY; 'I.</a>
                                        <p>Alice began to repeat it, but her head in the sky. Alice went on without attending to her; 'but.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-9 col-xl-8 col-md-7">
                    <div class="card mb-0">
                        <div class="card-body p-2">
                            <div class="tab-content" id="nav-tabContent">
                                <div class="tab-pane fade show active" id="nav-dashboard" role="tabpanel" aria-labelledby="nav-dashboard-tab" tabindex="0">
                                    <div class="row g-2">
                                        <div class="col-lg-6">
                                            <div class="card mb-2">
                                                <div class="card-body px-3">
                                                    <div class="table-responsive">
                                                        <table class="table profile-table mb-0">
                                                            <tbody>
                                                                <tr>
                                                                    <td>@lang('menu.full_name') :</td>
                                                                    <td>{{ $user->prefix.' '.$user->name.' '.$user->last_name }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>@lang('menu.phone') :</td>
                                                                    <td>{{ $user->phone }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>@lang('menu.email') :</td>
                                                                    <td>{{ $user->email}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>@lang('menu.guardian_name') :</td>
                                                                    <td>{{ $user->guardian_name }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>@lang('menu.gender') :</td>
                                                                    <td>{{ $user->gender }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>@lang('menu.custom_field') :</td>
                                                                    <td>{{ $user->custom_field_1 }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>@lang('menu.date_of_birth') :</td>
                                                                    <td>{{ $user->date_of_birth }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>@lang('menu.marital_status') :</td>
                                                                    <td>{{ $user->marital_status }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>@lang('menu.blood_group') :</td>
                                                                    <td>{{ $user->blood_group }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>@lang('menu.id_proof_name') :</td>
                                                                    <td>{{ $user->id_proof_name }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>@lang('menu.current_address') :</td>
                                                                    <td>{{ $user->current_address }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>@lang('menu.id_proof_number') :</td>
                                                                    <td>{{ $user->id_proof_number }}</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card mb-0">
                                                <div class="card-header px-3 bg-secondary text-white">
                                                    <h5 class="card-title mt-0">Upcoming Holidays</h5>
                                                </div>
                                                <div class="card-body">
                                                    <ul class="upcoming-holidays">
                                                        <li class="alert-success px-3 py-2">Sunday <span>18 Jun 2023</span></li>
                                                        <li class="alert-danger px-3 py-2">Sunday <span>18 Jun 2023</span></li>
                                                        <li class="alert-info px-3 py-2">Sunday <span>18 Jun 2023</span></li>
                                                        <li class="alert-primary px-3 py-2">Sunday <span>18 Jun 2023</span></li>
                                                        <li class="alert-warning px-3 py-2">Sunday <span>18 Jun 2023</span></li>
                                                        <li class="alert-dark px-3 py-2">Sunday <span>18 Jun 2023</span></li>
                                                        <li class="alert-danger px-3 py-2">Sunday <span>18 Jun 2023</span></li>
                                                        <li class="alert-primary px-3 py-2">Sunday <span>18 Jun 2023</span></li>
                                                        <li class="alert-warning px-3 py-2">Sunday <span>18 Jun 2023</span></li>
                                                        <li class="alert-dark px-3 py-2">Sunday <span>18 Jun 2023</span></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="card mb-2">
                                                <div class="card-header px-3 bg-secondary text-white">
                                                    <h5 class="card-title mt-0">@lang('menu.address') @lang('menu.details')</h5>
                                                </div>
                                                <div class="card-body py-0 px-3">
                                                    <div class="table-responsive">
                                                        <table class="table profile-table mb-0">
                                                            <tbody>
                                                                <tr>
                                                                    <td>@lang('menu.current') @lang('menu.address') :</td>
                                                                    <td>{{ $user->permanent_address }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>@lang('menu.permanent_address') :</td>
                                                                    <td>{{ json_decode($bussinessSettings->business, true)['address'] }}</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card mb-2">
                                                <div class="card-header px-3 bg-secondary text-white">
                                                    <h5 class="card-title mt-0">@lang('menu.bank') @lang('menu.account_details')</h5>
                                                </div>
                                                <div class="card-body py-0 px-3">
                                                    <div class="table-responsive">
                                                        <table class="table profile-table mb-0">
                                                            <tbody>
                                                                <tr>
                                                                    <td>@lang('menu.account_holders_name') :</td>
                                                                    <td>{{ $user->bank_ac_holder_name }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>@lang('menu.bank_name') :</td>
                                                                    <td>{{ $user->bank_name }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>@lang('menu.bank_branch_name') :</td>
                                                                    <td></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>@lang('menu.account_no') :</td>
                                                                    <td>{{ $user->bank_ac_no }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>@lang('menu.bank_identifier_code') :</td>
                                                                    <td>{{ $user->bank_identifier_code }}</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card mb-2">
                                                <div class="card-header px-3 bg-secondary text-white">
                                                    <h5 class="card-title mt-0">@lang('menu.social_media_link')</h5>
                                                </div>
                                                <div class="card-body py-0 px-3">
                                                    <div class="table-responsive">
                                                        <table class="table profile-table mb-0">
                                                            <tbody>
                                                                <tr>
                                                                    <td>@lang('menu.facebook_link') :</td>
                                                                    <td>{{ $user->facebook_link }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>@lang('menu.twitter_link') :</td>
                                                                    <td>{{ $user->twitter_link }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>@lang('menu.instagram_link') :</td>
                                                                    <td>{{ $user->instagram_link }}</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card mb-0">
                                                <div class="card-header px-3 bg-secondary text-white">
                                                    <h5 class="card-title mt-0">Awards</h5>
                                                </div>
                                                <div class="card-body">
                                                    <ul class="employee-awards">
                                                        <li>
                                                            <span class="left">
                                                                <span class="employee-name">Stekka</span>
                                                                <span class="award-name">Prize money double</span>
                                                            </span>
                                                            <span class="date">{{ Date('M Y') }}</span>
                                                        </li>
                                                        <li>
                                                            <span class="left">
                                                                <span class="employee-name">Stekka</span>
                                                                <span class="award-name">Prize money double</span>
                                                            </span>
                                                            <span class="date">{{ Date('M Y') }}</span>
                                                        </li>
                                                        <li>
                                                            <span class="left">
                                                                <span class="employee-name">Stekka</span>
                                                                <span class="award-name">Prize money double</span>
                                                            </span>
                                                            <span class="date">{{ Date('M Y') }}</span>
                                                        </li>
                                                        <li>
                                                            <span class="left">
                                                                <span class="employee-name">Stekka</span>
                                                                <span class="award-name">Prize money double</span>
                                                            </span>
                                                            <span class="date">{{ Date('M Y') }}</span>
                                                        </li>
                                                        <li>
                                                            <span class="left">
                                                                <span class="employee-name">Stekka</span>
                                                                <span class="award-name">Prize money double</span>
                                                            </span>
                                                            <span class="date">{{ Date('M Y') }}</span>
                                                        </li>
                                                        <li>
                                                            <span class="left">
                                                                <span class="employee-name">Stekka</span>
                                                                <span class="award-name">Prize money double</span>
                                                            </span>
                                                            <span class="date">{{ Date('M Y') }}</span>
                                                        </li>
                                                        <li>
                                                            <span class="left">
                                                                <span class="employee-name">Stekka</span>
                                                                <span class="award-name">Prize money double</span>
                                                            </span>
                                                            <span class="date">{{ Date('M Y') }}</span>
                                                        </li>
                                                        <li>
                                                            <span class="left">
                                                                <span class="employee-name">Stekka</span>
                                                                <span class="award-name">Prize money double</span>
                                                            </span>
                                                            <span class="date">{{ Date('M Y') }}</span>
                                                        </li>
                                                        <li>
                                                            <span class="left">
                                                                <span class="employee-name">Stekka</span>
                                                                <span class="award-name">Prize money double</span>
                                                            </span>
                                                            <span class="date">{{ Date('M Y') }}</span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="nav-leaves" role="tabpanel" aria-labelledby="nav-leaves-tab" tabindex="0">3...</div>
                                <div class="tab-pane fade" id="nav-self" role="tabpanel" aria-labelledby="nav-self-tab" tabindex="0">2...</div>
                                <div class="tab-pane fade" id="nav-attendance" role="tabpanel" aria-labelledby="nav-attendance-tab" tabindex="0">4...</div>
                                <div class="tab-pane fade" id="nav-settings" role="tabpanel" aria-labelledby="nav-settings-tab" tabindex="0">
                                    <h6>Change Password</h6>
                                    <hr class="mt-2"/>
                                    <div class="row g-2">
                                        <label for="" class="col-form-label col-sm-4 text-end pt-1">Current Password</label>
                                        <div class="col-sm-8">
                                            <input type="password" class="form-control">
                                        </div>
                                        <label for="" class="col-form-label col-sm-4 text-end pt-1">New Password</label>
                                        <div class="col-sm-8">
                                            <input type="password" class="form-control">
                                        </div>
                                        <label for="" class="col-form-label col-sm-4 text-end pt-1">Confirm Password</label>
                                        <div class="col-sm-8">
                                            <input type="password" class="form-control">
                                        </div>
                                        <div class="col-sm-8 offset-sm-4">
                                            <a href="#" role="button" class="btn btn-sm btn-success">Save Changes</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="noticeModal" tabindex="-1" aria-labelledby="noticeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="noticeModalLabel">Notice Modal</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Et ipsam numquam aperiam magnam fugit consequuntur ratione consectetur, tempora aspernatur vero ea doloribus cupiditate magni qui, similique quasi quae rerum nostrum!</p>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quasi fugit accusantium nostrum molestias quibusdam? Fugiat minima deserunt cum porro! Velit aliquam odio vero minima, unde aspernatur neque labore rem dolorum.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-sm btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

@endsection
@push('scripts')

@endpush
