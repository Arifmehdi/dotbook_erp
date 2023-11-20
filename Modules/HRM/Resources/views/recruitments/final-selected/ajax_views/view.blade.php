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
            $jobApplyStatus = $finalSelectedApplicant['status'];
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
                            @if ($finalSelectedApplicant['photo'] != null)
                                <img class="img-fluid rounded-circle" id="photo"
                                    src="{{ asset('website/') }}/{{ $finalSelectedApplicant['photo'] }}" width="175"
                                    height="180" alt="Image not found">
                            @else
                                <div class="part-img">
                                    <img src="{{ asset('images/profile-picture.jpg') }}" alt="Not found">
                                </div>
                            @endif
                        </div>
                        <h6 class="font-weight-semibold mb-0 mt-3">
                            {{ $finalSelectedApplicant->job_applied['job_title'] ?? 'no available' }}</h6>
                        <h6 class="font-weight-semibold mb-0">
                            {{ $finalSelectedApplicant['first_name'] ?? 'no available' }}
                            {{ $finalSelectedApplicant['last_name'] ?? 'no available' }}</h6>
                        <h6 class="font-weight-semibold mb-0"></h6>
                        <span class="d-block mb-3"><b>{{ __('Phone') }}:
                                {{ $finalSelectedApplicant['mobile'] ?? 'no available' }} </b></span>
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
                                    <td>: {{ $finalSelectedApplicant['first_name'] ?? 'no available' }}
                                        {{ $finalSelectedApplicant['last_name'] ?? 'no available' }}</td>
                                    <th class="bg-transparent text-dark">@lang('menu.phone') </th>
                                    <td>: {{ $finalSelectedApplicant['mobile'] ?? 'no available' }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-transparent text-dark">@lang('menu.email') </th>
                                    <td>: {{ $finalSelectedApplicant['email'] ?? 'no available' }}</td>
                                    <th class="bg-transparent text-dark">{{ __('Source') }} </th>
                                    <td>: {{ $finalSelectedApplicant['sourch'] ?? 'no available' }} </td>
                                </tr>
                                <tr>
                                    <th class="bg-transparent text-dark">@lang('menu.city') </th>
                                    <td>: {{ $finalSelectedApplicant['city'] ?? 'no available' }}</td>
                                    <th class="bg-transparent text-dark">@lang('menu.country') </th>
                                    <td>: {{ $finalSelectedApplicant['country'] ?? 'no available' }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-transparent text-dark">{{ __('Website') }} </th>
                                    <td>: {{ $finalSelectedApplicant['website_url'] ?? 'no available' }}</td>
                                    <th class="bg-transparent text-dark">{{ __('LinkedIn') }} </th>
                                    <td>: {{ $finalSelectedApplicant['linkedin_url'] ?? 'no available' }}</td>
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
                                @foreach (json_decode($finalSelectedApplicant['education'], true) as $education)
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
                                @foreach (json_decode($finalSelectedApplicant['experience'], true) as $experience)
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
            {{-- <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header header-elements-inline bg-dark text-white ">
                            <h5 class="card-title">{{ __('Job Responsibilities') }}</h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped ">
                                <tbody>
                                    <tr>
                                        <td><strong>{{ __('Department') }}</strong></td>
                                        <td>{{ $finalSelectedApplicant->hrmDepartment->name ?? 'no available' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ __('Section') }}</strong></td>
                                        <td>{{ $finalSelectedApplicant->section->name ?? 'no available' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ __('Designation') }}</strong></td>
                                        <td>{{ $finalSelectedApplicant->designation->name ?? 'no available' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ __('Joining Date') }}</strong></td>
                                        <td>{{ $finalSelectedApplicant->joining_date ?? 'no available' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ __('Starting Salary') }}</strong> </td>
                                        <td>{{ $finalSelectedApplicant->starting_salary ?? 'no available' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ __('Present Salary') }}</strong> </td>
                                        <td>{{ $finalSelectedApplicant->salary ?? 'no available' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ __('Grade') }}</strong> </td>
                                        <td>{{ $finalSelectedApplicant->grade->name ?? 'no available' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div> --}}
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header header-elements-inline bg-dark text-white">
                        <h5 class="card-title">{{ __('Skills') }}</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            @php
                                $skills = explode(' ', $finalSelectedApplicant['skill']);
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
                                    <td>{{ $finalSelectedApplicant['location'] ?? 'no available' }}</td>
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
                    @if (isset($finalSelectedApplicant['cover_letter']))
                        <p class="py-3">
                            @php
                                $cover_letter = $finalSelectedApplicant['cover_letter'];
                                $extension = pathinfo($cover_letter, PATHINFO_EXTENSION);
                            @endphp
                            @if ($extension == 'pdf')
                                <iframe src="{{ asset('/website/' . $finalSelectedApplicant['cover_letter']) }}"
                                    width="100%" height="600"></iframe>
                            @else
                                <img src="{{ asset('/website/' . $finalSelectedApplicant['cover_letter']) }}"
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
                    @if (isset($finalSelectedApplicant['resume']))
                        <p class="py-3">
                            @php
                                $resume = $finalSelectedApplicant['resume'];
                                $extension = pathinfo($resume, PATHINFO_EXTENSION);
                            @endphp
                            @if ($extension == 'pdf')
                                <iframe src="{{ asset('/website/' . $finalSelectedApplicant['resume']) }}"
                                    width="100%" height="600"></iframe>
                            @else
                                <img src="{{ asset('/website/' . $finalSelectedApplicant['resume']) }}"
                                    height="200" width="100%" />
                            @endif
                        </p>
                    @endif
                </table>

            </div>
            <div class="modal-footer p-3 d-flex justify-content-between">
                {{-- <div class="row d-flex"> --}}

                <div class="d-flex justify-content-between">
                    <a class="btn btn-sm btn-primary me-2">Action</a>
                    <form
                        action="{{ route('hrm.applicant_single_offer_letter', ['id' => $finalSelectedApplicant['id']]) }}"
                        method="post" class="d-flex justify-content-between">
                        @csrf
                        <select name="status" class="form-controll me-2" style="padding: 0px 6px;"
                            aria-label="Default select example">
                            <option selected>{{ __('Select One') }}</option>
                            <option value="{{ JobAppliedStatus::SendMailForOfferLetter->value }}">
                                {{ __(' Send an Offer Letter') }}</option>
                            <option value="{{ JobAppliedStatus::Rejected->value }}"> {{ __('Rejected') }}</option>
                        </select>
                        <button type="submit" class="btn btn-sm btn-primary">Apply</button>
                    </form>
                </div>
                <div>
                    <a href="#" target="_blank" class="btn btn-sm btn-primary float-end" id="print_visit"
                        style="padding: 6px;"><i class="fas fa-print"></i> @lang('menu.print')</a>
                    <button type="submit" data-bs-dismiss="modal"
                        class="btn btn-sm btn-danger">@lang('menu.close')</button>
                </div>
                {{-- </div> --}}
            </div>
        </div>
    </div>
</div>
