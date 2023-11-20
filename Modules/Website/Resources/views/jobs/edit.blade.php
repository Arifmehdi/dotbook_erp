<style>
    #submit_customer_basic_form .form-group label {
        text-align: right;
    }
</style>
<div class="modal-dialog col-60-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">@lang('menu.add_customer') <span class="type_name"></span></h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                    class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="submit_customer_basic_form" action="{{ route('website.jobs.update', $job->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group row mt-1">
                    <div class="col-lg-12">
                        <div class="form-group row p-1">
                            <label class="col-sm-3" for="job_type"><b>Job Type </b> <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select class="form-control form-select" name="job_type" id="job_type" required>
                                    <option @if ($job->job_type == 'fulltime') selected @endif value="fulltime" selected>
                                        Full Time</option>
                                    <option @if ($job->job_type == 'parttime') selected @endif value="parttime">Part Time
                                    </option>
                                    <option @if ($job->job_type == 'projectbase') selected @endif value="projectbase">
                                        Project Base</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row p-1">
                            <label class="col-sm-3" for="category"><b>Category</b> <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select class="form-control form-select" name="category" id="category" required>
                                    @foreach ($categories as $cat)
                                        <option @if ($job->job_category_id == $cat->id) selected @endif
                                            value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row p-1">
                            <label class="col-sm-3" for="job_title"><b>Job Title </b> <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" id="job_title" name="job_title" class="form-control"
                                    placeholder="Job Title" value="{{ $job->job_title }}" required>
                            </div>
                        </div>
                        <div class="form-group row p-1">
                            <label class="col-sm-3" for="vacancy"><b>Vacancy </b> <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" id="vacancy" name="vacancy" class="form-control"
                                    placeholder="Vacancy" value="{{ $job->vacancy }}" required>
                            </div>
                        </div>
                        <div class="form-group row p-1">
                            <label class="col-sm-3" for="location"><b>Location </b> <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <textarea type="text" id="location" name="location" class="form-control ckEditor" required>{{ $job->location }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row p-1">
                            <label class="col-sm-3" for="email"><b>Email </b> <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="email" id="email" name="email" class="form-control"
                                    placeholder="Email" value="{{ $job->email }}" required>
                            </div>
                        </div>
                        <div class="form-group row p-1">
                            <label class="col-sm-3" for="website"><b>Website </b> <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" id="website" name="website" class="form-control"
                                    placeholder="Website" value="{{ $job->website }}">
                            </div>
                        </div>
                        <div class="form-group row p-1">
                            <label class="col-sm-3" for="city"><b>City </b></label>
                            <div class="col-sm-9">
                                <input type="text" id="city" name="city" class="form-control"
                                    placeholder="City" value="{{ $job->city }}">
                            </div>
                        </div>
                        <div class="form-group row p-1">
                            <label class="col-sm-3" for="salary_type"><b>Salary Type </b></label>
                            <div class="col-sm-9">
                                <select class="form-control form-select" name="salary_type" id="salary_type">
                                    <option @if ($job->salary_type == 'fixes') selected @endif value="fixes">Fixed
                                    </option>
                                    <option @if ($job->salary_type == 'negotible') selected @endif value="negotible">
                                        Negotible</option>
                                    <option @if ($job->salary_type == 'oncall') selected @endif value="oncall">On Call
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row p-1">
                            <label class="col-sm-3" for="salary"><b>Salary </b> <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="number" id="salary" name="salary" class="form-control"
                                    placeholder="Salary" value="{{ $job->salary }}">
                            </div>
                        </div>
                        <div class="form-group row p-1">
                            <label class="col-sm-3" for="deadline"><b>Deadline </b> <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="date" id="deadline" class="form-control"
                                    value="{{ $job->deadline }}" name="deadline" placeholder="Enter Deadline"
                                    required>
                            </div>
                        </div>
                        <div class="form-group row p-1">
                            <label class="col-sm-3" for="image"><b>Photo</b></label>
                            <div class="col-sm-9">
                                <input type="file" id="image" class="form-control" name="image"
                                    onchange="readURL(this);">
                                <img src="{{ asset($job->image) }}" id="one"
                                    class="preview-image @if ($job->image == null) d-none @endif"
                                    style="height: 45px; width:100px">
                            </div>
                        </div>
                        <div class="form-group row p-1">
                            <label class="col-sm-3" for="responsibility"><b>Responsibilities</b></label>
                            <div class="col-sm-9">
                                <textarea required type="text" id="responsibility" name="responsibility" class="form-control ckEditor">{{ $job->responsibility }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row p-1">
                            <label class="col-sm-3" for="facilities"><b>Facilities</b></label>
                            <div class="col-sm-9">
                                <textarea required type="text" id="facilities" name="facilities" class="form-control ckEditor">{{ $job->facilities }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row p-1">
                            <label class="col-sm-3" for="education"><b>Education</b></label>
                            <div class="col-sm-9">
                                <textarea required type="text" id="education" name="education" class="form-control ckEditor">{{ $job->education_req }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row p-1">
                            <label class="col-sm-3" for="skill"><b>Skills</b></label>
                            <div class="col-sm-9">
                                <textarea required type="text" id="skill" name="skill" class="form-control ckEditor">{{ $job->skill }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row p-1">
                            <label class="col-sm-3" for="experience"><b>Experience</b></label>
                            <div class="col-sm-9">
                                <textarea required type="text" id="experience" name="experience" class="form-control ckEditor">{{ $job->experience }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row p-1">
                            <label class="col-sm-3" for="description"><b>Description</b></label>
                            <div class="col-sm-9">
                                <textarea required type="text" id="description" name="description" class="form-control ckEditor">{{ $job->description }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row mt-3">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="loading-btn-box">
                            <button type="button" class="btn btn-sm loading_button display-none"><i
                                    class="fas fa-spinner"></i></button>
                            <button type="submit"
                                class="btn btn-sm btn-success submit_button">@lang('menu.save')</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#one')
                    .attr('src', e.target.result)
                    .width(80)
                    .height(80);
            };
            $('.preview-image').removeClass('d-none');
            reader.readAsDataURL(input.files[0]);
        }
    }

    $('#submit_customer_basic_form').on('submit', function(e) {
        e.preventDefault();
        $('.c_loading_button').show();
        var url = $(this).attr('action');
        $.ajax({
            url: url,
            type: 'POST',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                if (data.error == 1) {
                    toastr.error("Something went wrong");
                    $('.c_loading_button').hide();
                } else {
                    $('.error').html('');
                    toastr.success(data);
                    $('.c_loading_button').hide();
                    $('#add_customer_basic_modal').modal('hide');
                    $('.submit_button').prop('type', 'submit');
                    location.reload();
                }
            },
            error: function(err) {
                $('.c_loading_button').hide();
                $('.submit_button').prop('type', 'submit');
                $('.error').html('');
                if (err.status == 0) {
                    toastr.error('Net Connetion Error. Reload This Page.');
                    return;
                } else if (err.status == 500) {
                    toastr.error('Server Error. Please contact to the support team.');
                    return;
                }
                toastr.error('Please check again all form fields.', 'Some thing went wrong.');
                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_' + key + '').html(error[0]);
                });
            }
        });
    });
</script>
