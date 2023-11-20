@php
    use Modules\HRM\Enums\JobAppliedStatus;
@endphp
<div class="">
    <div class="row">
        <div class="col-lg-3">
            <div class="card">
                <div class="card-body text-center">
                    <div class="card-img-actions d-inline-block mb-3">
                    </div>
                    <div class="profile-top p-0">
                        @if ($convert_employee['photo'] != null)
                            <img class="img-fluid rounded-circle" id="photo"
                                src="{{ asset('website/') }}/{{ $convert_employee['photo'] }}" width="175"
                                height="180" alt="Image not found">
                        @else
                            <div class="part-img">
                                <img src="{{ asset('images/profile-picture.jpg') }}" alt="Not found">
                            </div>
                        @endif
                    </div>
                    <h6 class="font-weight-semibold mb-0 mt-3">
                        {{ $convert_employee->job_applied['job_title'] ?? 'no available' }}</h6>
                    <h6 class="font-weight-semibold mb-0">{{ $convert_employee['first_name'] ?? 'no available' }}
                        {{ $convert_employee['last_name'] ?? 'no available' }}</h6>
                    <h6 class="font-weight-semibold mb-0"></h6>
                    <span class="d-block mb-3"><b>{{ __('Phone') }}:
                            {{ $convert_employee['mobile'] ?? 'no available' }} </b></span>
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
                                <td>: {{ $convert_employee['first_name'] ?? 'no available' }}
                                    {{ $convert_employee['last_name'] ?? 'no available' }}</td>
                                <th class="bg-transparent text-dark">@lang('menu.phone') </th>
                                <td>: {{ $convert_employee['mobile'] ?? 'no available' }}</td>
                            </tr>
                            <tr>
                                <th class="bg-transparent text-dark">@lang('menu.email') </th>
                                <td>: {{ $convert_employee['email'] ?? 'no available' }}</td>
                                <th class="bg-transparent text-dark">{{ __('Source') }} </th>
                                <td>: {{ $convert_employee['sourch'] ?? 'no available' }} </td>
                            </tr>
                            <tr>
                                <th class="bg-transparent text-dark">@lang('menu.city') </th>
                                <td>: {{ $convert_employee['city'] ?? 'no available' }}</td>
                                <th class="bg-transparent text-dark">@lang('menu.country') </th>
                                <td>: {{ $convert_employee['country'] ?? 'no available' }}</td>
                            </tr>
                            <tr>
                                <th class="bg-transparent text-dark">{{ __('Website') }} </th>
                                <td>: {{ $convert_employee['website_url'] ?? 'no available' }}</td>
                                <th class="bg-transparent text-dark">{{ __('LinkedIn') }} </th>
                                <td>: {{ $convert_employee['linkedin_url'] ?? 'no available' }}</td>
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
                            @foreach (json_decode($convert_employee['education'], true) as $education)
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
                            @foreach (json_decode($convert_employee['experience'], true) as $experience)
                                <tr>
                                    <td>{{ $experience['designation'] }}</td>
                                    <td>{{ $experience['company'] }}</td>
                                    <td>{{ $experience['summary'] }}</td>
                                    <td>{{ $experience['exp_start_month'] }}, {{ $experience['exp_start_year'] }}</td>
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
                            $skills = explode(' ', $convert_employee['skill']);
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
                                <td>{{ $convert_employee['location'] ?? 'no available' }}</td>
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
                @if (isset($convert_employee['cover_letter']))
                    <p class="py-3">
                        @php
                            $cover_letter = $convert_employee['cover_letter'];
                            $extension = pathinfo($cover_letter, PATHINFO_EXTENSION);
                        @endphp
                        @if ($extension == 'pdf')
                            <iframe src="{{ asset('/website/' . $convert_employee['cover_letter']) }}" width="100%"
                                height="600"></iframe>
                        @else
                            <img src="{{ asset('/website/' . $convert_employee['cover_letter']) }}" height="200"
                                width="100%" />
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
                @if (isset($convert_employee['resume']))
                    <p class="py-3">
                        @php
                            $resume = $convert_employee['resume'];
                            $extension = pathinfo($resume, PATHINFO_EXTENSION);
                        @endphp
                        @if ($extension == 'pdf')
                            <iframe src="{{ asset('/website/' . $convert_employee['resume']) }}" width="100%"
                                height="600"></iframe>
                        @else
                            <img src="{{ asset('/website/' . $convert_employee['resume']) }}" height="200"
                                width="100%" />
                        @endif
                    </p>
                @endif
            </table>

        </div>
        <div class="modal-footer p-3 d-flex justify-content-between">
            <div class="row d-flex">
                {{-- <div class="d-flex justify-content-between">
                    <a class="btn btn-sm btn-primary me-2">Action</a>
                    <form action="{{ route('hrm.applicant_single_offer_letter', ['id'=>$convert_employee['id']]) }}" method="post" class="d-flex justify-content-between">
                        @csrf
                        <select name="status" class="form-select me-2 form-select" style="padding: 6px;" aria-label="Default select example">
                            <option selected>{{ __('Select One') }}</option>
                            <option value="{{ JobAppliedStatus::Hired->value }}"> {{ __(' Hired') }}</option>
                            <option value="{{ JobAppliedStatus::Rejected->value }}"> {{ __('Rejected') }}</option>
                        </select>
                        <button type="submit" class="btn btn-sm btn-primary">Apply</button>
                    </form>
                </div> --}}
                <div>
                    <a href="#" target="_blank" class="btn btn-sm btn-primary float-end" id="print_visit"
                        style="padding: 6px;"><i class="fas fa-print"></i> @lang('menu.print')</a>
                    <button type="submit" data-bs-dismiss="modal"
                        class="btn btn-sm btn-danger">@lang('menu.close')</button>
                </div>
            </div>
        </div>
    </div>
</div>
