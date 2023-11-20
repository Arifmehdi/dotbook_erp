@php
    use Modules\HRM\Enums\JobAppliedStatus;
@endphp
<div class="modal-header bg-dark">
    <h5 class="modal-title"><i class="icon-plus-circle2 mr-2"></i> &nbsp;Applicant's Details</h5>
    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times">
        </span></a>
</div>
<div class="modal-header">
    <ul class="breadcrumb wizard" style="width: 100%; margin-bottom: 0px !important;">
        @php
            $jobApplyStatus = $selectedInterviewer['status'];
        @endphp

        <li class="@if (JobAppliedStatus::Default->value <= $jobApplyStatus) completed @endif"><a href="javascript:void(0);">Applied</a></li>
        <li class="@if (JobAppliedStatus::SelectedInterview->value <= $jobApplyStatus) completed @endif"><a href="javascript:void(0);">Selected For
                Interview</a></li>
        <li class="@if (JobAppliedStatus::SendMailForInterview->value <= $jobApplyStatus) completed @endif"><a href="javascript:void(0);">Mail Sent For
                Interview</a></li>
        <li class="@if (JobAppliedStatus::InterviewParticipant->value <= $jobApplyStatus) completed @endif"><a href="javascript:void(0);">Interview
                Participated</a></li>
        <li class="@if (JobAppliedStatus::FinalSelected->value <= $jobApplyStatus) completed @endif"><a href="javascript:void(0);">Final Selectd</a>
        </li>
        <li class="@if (JobAppliedStatus::SendMailForOfferLetter->value <= $jobApplyStatus) completed @endif"><a href="javascript:void(0);">Offer Letter
                Sent</a></li>
        <li class="@if (JobAppliedStatus::Hired->value <= $jobApplyStatus) completed @endif"><a href="javascript:void(0);">Applicant Hired</a>
        </li>
    </ul>
</div>
<div class="modal-body">
    <div class="">
        <div class="row">
            <div class="col-lg-3">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="card-img-actions d-inline-block mb-3">
                        </div>
                        <div class="profile-top p-0">
                            @if ($selectedInterviewer['photo'] != null)
                                <img class="img-fluid rounded-circle" id="photo"
                                    src="{{ asset('website/') }}/{{ $selectedInterviewer['photo'] }}" width="175"
                                    height="180" alt="Image not found">
                            @else
                                <div class="part-img">
                                    <img src="{{ asset('images/profile-picture.jpg') }}" alt="Not found">
                                </div>
                            @endif
                        </div>
                        <h6 class="font-weight-semibold mb-0 mt-3">
                            {{ $selectedInterviewer->job_applied['job_title'] ?? 'no available' }}</h6>
                        <h6 class="font-weight-semibold mb-0">{{ $selectedInterviewer['first_name'] ?? 'no available' }}
                            {{ $selectedInterviewer['last_name'] ?? 'no available' }}</h6>
                        <h6 class="font-weight-semibold mb-0"></h6>
                        <span class="d-block mb-3"><b>{{ __('Phone') }}:
                                {{ $selectedInterviewer['mobile'] ?? 'no available' }} </b></span>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="row g-3">
                    <div class="col-12 border rounded">
                        <div class="py-2">
                            <h5 class="card-title border-bottom py-2">@lang('menu.personal_information')</h5>
                        </div>
                        <div>
                            <table class="table table-borderless">
                                <tr>
                                    <th class="bg-transparent text-dark">@lang('menu.name') </th>
                                    <td>: {{ $selectedInterviewer['first_name'] ?? 'no available' }}
                                        {{ $selectedInterviewer['last_name'] ?? 'no available' }}</td>
                                    <th class="bg-transparent text-dark">@lang('menu.phone') </th>
                                    <td>: {{ $selectedInterviewer['mobile'] ?? 'no available' }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-transparent text-dark">@lang('menu.email') </th>
                                    <td>: {{ $selectedInterviewer['email'] ?? 'no available' }}</td>
                                    <th class="bg-transparent text-dark">{{ __('Source') }} </th>
                                    <td>: {{ $selectedInterviewer['sourch'] ?? 'no available' }} </td>
                                </tr>
                                <tr>
                                    <th class="bg-transparent text-dark">@lang('menu.city') </th>
                                    <td>: {{ $selectedInterviewer['city'] ?? 'no available' }}</td>
                                    <th class="bg-transparent text-dark">@lang('menu.country') </th>
                                    <td>: {{ $selectedInterviewer['country'] ?? 'no available' }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-transparent text-dark">{{ __('Website') }} </th>
                                    <td>: {{ $selectedInterviewer['website_url'] ?? 'no available' }}</td>
                                    <th class="bg-transparent text-dark">{{ __('LinkedIn') }} </th>
                                    <td>: {{ $selectedInterviewer['linkedin_url'] ?? 'no available' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="col-12 border rounded">
                        <div class="py-2">
                            <h5 class="card-title border-bottom py-2">{{ __('Educational Information') }}</h5>
                        </div>
                        <table class="table  table-borderless">
                            <thead>
                                <tr>
                                    <th class="bg-transparent text-dark">{{ __('Institute') }} </th>
                                    <th class="bg-transparent text-dark">{{ __('Department') }} </th>
                                    <th class="bg-transparent text-dark">{{ __('Degree') }} </th>
                                    <th class="bg-transparent text-dark">{{ __('Passing Year') }} </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (json_decode($selectedInterviewer['education'], true) as $education)
                                    <tr>
                                        <td>{{ $education['institute'] }}</td>
                                        <td>{{ $education['department'] }}</td>
                                        <td>{{ $education['degree'] }}</td>
                                        <td>{{ $education['edu_end_year'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-12 border rounded">
                        <div class="py-2">
                            <h5 class="card-title border-bottom py-2">{{ __('Job Expreience') }}</h5>
                        </div>
                        <table class="table  table-borderless">
                            <thead>
                                <tr>
                                    <th class="bg-transparent text-dark">{{ __('Designation') }} </th>
                                    <th class="bg-transparent text-dark">{{ __('Company') }} </th>
                                    <th class="bg-transparent text-dark">{{ __('Summary') }} </th>
                                    <th class="bg-transparent text-dark">{{ __('Start Date') }} </th>
                                    <th class="bg-transparent text-dark">{{ __('End Date') }} </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (json_decode($selectedInterviewer['experience'], true) as $experience)
                                    <tr>
                                        <td>{{ $experience['designation'] }}</td>
                                        <td>{{ $experience['company'] }}</td>
                                        <td>{{ $experience['summary'] }}</td>
                                        <td>{{ $experience['exp_start_month'] }}, {{ $experience['exp_start_year'] }}
                                        </td>
                                        <td>{{ $experience['exp_end_month'] }}, {{ $experience['exp_end_year'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-2 g-1">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header header-elements-inline bg-dark text-white">
                        <h5 class="card-title">{{ __('Skills') }}</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            @php
                                $skills = explode(' ', $selectedInterviewer['skill']);
                            @endphp
                            <tbody>
                                @foreach ($skills as $skill)
                                    <tr>
                                        <td><strong>{{ $skill }}</strong></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header header-elements-inline bg-dark text-white">
                        <h5 class="card-title">{{ __('Location') }}</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <td>{{ $selectedInterviewer['location'] ?? 'no available' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 border rounded">
                <div class="py-2">
                    <h5 class="card-title border-bottom py-2">{{ __('Cover Letter') }}</h5>
                </div>
                <table class="table  table-borderless">
                    @if (isset($selectedInterviewer['cover_letter']))
                        <p class="py-3">
                            @php
                                $cover_letter = $selectedInterviewer['cover_letter'];
                                $extension = pathinfo($cover_letter, PATHINFO_EXTENSION);
                            @endphp
                            @if ($extension == 'pdf')
                                <iframe src="{{ asset('/website/' . $selectedInterviewer['cover_letter']) }}"
                                    width="100%" height="600"></iframe>
                            @else
                                <img src="{{ asset('/website/' . $selectedInterviewer['cover_letter']) }}"
                                    height="200" width="100%" />
                            @endif
                        </p>
                    @endif
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-12 border rounded">
                <div class="py-2">
                    <h5 class="card-title border-bottom py-2">{{ __('Resume') }}</h5>
                </div>
                <table class="table  table-borderless">
                    @if (isset($selectedInterviewer['resume']))
                        <p class="py-3">
                            @php
                                $resume = $selectedInterviewer['resume'];
                                $extension = pathinfo($resume, PATHINFO_EXTENSION);
                            @endphp
                            @if ($extension == 'pdf')
                                <iframe src="{{ asset('/website/' . $selectedInterviewer['resume']) }}"
                                    width="100%" height="600"></iframe>
                            @else
                                <img src="{{ asset('/website/' . $selectedInterviewer['resume']) }}" height="200"
                                    width="100%" />
                            @endif
                        </p>
                    @endif
                </table>

            </div>
            <div class="modal-footer p-3 d-flex justify-content-between">
                <div class="">
                    {{-- <a class="btn btn-sm btn-primary me-2">Action</a> --}}
                    <form
                        action="{{ route('hrm.applicant_send_single_mail_for_Interview', ['id' => $selectedInterviewer['id']]) }}"
                        method="post" class="d-flex justify-content-between">
                        @csrf
                        <div class="col-xl-6 col-md-6">
                            <label><strong> {{ __('Action') }}</strong> <span class="text-danger">*</span></label>
                            <select name="status" class="form-controll me-2" style="padding: 0px 6px;"
                                aria-label="Default select example">
                                <option selected>{{ __('Select One') }}</option>
                                <option value="{{ JobAppliedStatus::SendMailForInterview->value }}">
                                    {{ __('Send a Mail For Interview') }}</option>
                                <option value="{{ JobAppliedStatus::Pending->value }}"> {{ __('Pending') }}</option>
                            </select>
                        </div>
                        <div class="col-xl-6 col-md-6 me-2">
                            <label><strong> {{ __('Email Template') }}</strong> <span
                                    class="text-danger">*</span></label>
                            <select name="email_template_id" required class="form-controll submit_able"
                                id="email_template_id" autofocus="">
                                <option disabled value="">{{ __('Select Email Template') }}</option>
                                @foreach ($email_templates as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                            <span class="error email_template_id"></span>
                        </div>
                        <div class="pt-4">
                            <button type="submit" class="btn btn-sm btn-primary">Apply</button>
                        </div>
                    </form>
                </div>
                <div>
                    <button href="#" target="_blank" class="btn btn-sm btn-primary float-end mx-3"
                        id="print_visit"><i class="fas fa-print"></i> @lang('menu.print')</button>
                    <button type="submit" data-bs-dismiss="modal"
                        class="btn btn-sm btn-danger">@lang('menu.close')</button>
                </div>
            </div>
        </div>
    </div>
