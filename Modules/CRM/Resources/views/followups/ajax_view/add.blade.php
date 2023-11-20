<style>
    .uploaded-image-preview {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        padding-top: 20px;
    }

    .single-img-box {
        width: calc(100% / 6 - 8.4px);
        height: 70px;
        border: 1px solid #323232;
        display: flex;
        justify-content: center;
        align-items: center;
        position: relative;
    }

    .single-img-box img {
        max-height: 100%;
    }

    .single-img-box .img-close {
        position: absolute;
        top: 5px;
        right: 5px;
        text-align: center;
        width: 20px;
        height: 20px;
        line-height: 22px;
        background: rgba(255, 255, 255, 0.5);
        border: 0;
        border-radius: 50%;
        opacity: 0;
        transition: .3s;
    }

    .single-img-box:hover .img-close {
        opacity: 1;
    }
</style>

@if ($followups->leads_individual_or_business == 'business')
    <div class="row p-1">
        <div class="col-sm-6">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title" id="name">Name : {{ $followups->busilness_lead->name }}</h6>
                    <p class="card-title" id="address">Location : {{ $followups->busilness_lead->location }}</p>
                    <p class="card-text" id="description">Description : {{ $followups->busilness_lead->description }}</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title" id="email_addresses">Email :
                        {{ $followups->busilness_lead->email_addresses }}</h6>
                    <p class="card-title" id="phone_numbers">Phone : {{ $followups->busilness_lead->phone_numbers }}</p>
                    <p class="card-text" id="additional_information">Additional Information :
                        {{ $followups->busilness_lead->additional_information }}</p>
                </div>
            </div>
        </div>
    </div>
@elseif($followups->leads_individual_or_business == 'individual')
    <div class="row p-1">
        <div class="col-sm-6">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title" id="name">Name : {{ $followups->individual_lead->name }}</h6>
                    <p class="card-title" id="address">Location : {{ $followups->individual_lead->address }}</p>
                    <p class="card-text" id="description">Description : {{ $followups->individual_lead->description }}
                    </p>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title" id="email_addresses">Email :
                        {{ $followups->individual_lead->email_addresses }}</h6>
                    <p class="card-title" id="phone_numbers">Phone : {{ $followups->individual_lead->phone_numbers }}
                    </p>
                    <p class="card-text" id="additional_information">Additional Information :
                        {{ $followups->individual_lead->additional_information }}</p>
                </div>
            </div>
        </div>
    </div>
@endif


<div class="card text-center p-1">
    <div class="card-header">Conversation Record</div>
</div>

@forelse ($followupRecord as $item)
    <div class="card text-center p-1">
        <div class="card-header">
            <div class="row">
                <div class="col-md-12">
                    @if ($item->status == 'Interested')
                        <span class="badge bg-green"> {{ $item->status }}</span>
                    @elseif($item->status == 'Pending')
                        <span class="badge bg-blue"> {{ $item->status }}</span>
                    @elseif($item->status == 'Not Connect')
                        <span class="badge bg-dark"> {{ $item->status }}</span>
                    @elseif($item->status == 'Not Interested')
                        <span class="badge bg-danger"> {{ $item->status }}</span>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">{{ 'Followup type : ' . $item->followup_type }}</div>
                <div class="col-md-4">{{ 'Category : ' . $item->categories->name }}</div>
            </div>
        </div>
        <div class="card-body">
            <h5 class="card-title">{{ 'Summary : ' . $item->title }}</h5>
            <p class="card-text">{{ 'Details : ' . $item->description }}</p>
        </div>
        <div class="card-footer text-muted">{{ 'Date : ' . $item->date }}</div>
    </div>
@empty
    <div class="card text-center p-0">
        <div class="card-header">Record Not Found</div>
    </div>
@endforelse

<form id="followup_form" action="{{ route('crm.followup.store') }}" method="POST" enctype="multipart/form-data">
    {{-- @method('PUT') --}}
    @csrf
    <div class="form-group row mt-1">
        <div class="col-md-6">
            <label><strong>Leads Type</strong> </label>

            <select name="leads_individual_or_business" class="form-control form-select"
                id="leads_individual_or_business">
                <option value="{{ $followups->leads_individual_or_business }}" selected>
                    {{ $followups->leads_individual_or_business }}</option>
            </select>
        </div>
        <div class="col-md-6">
            <label><strong>Name</strong> </label>
            <select name="leads_user_id" class="form-control form-select" id="leads_user_id">
                @if ($followups->leads_individual_or_business == 'business')
                    <option value="{{ $followups->business_id }}" selected>{{ $followups->busilness_lead->name }}
                    </option>
                @else
                    <option value="{{ $followups->individual_id }}" selected>{{ $followups->individual_lead->name }}
                    </option>
                @endif
            </select>
        </div>
    </div>

    <div class="form-group row mt-1">

        <div class="col-md-6">
            <label><strong>Title </strong> <span class="text-danger">*</span></label>
            <input required type="text" name="title" class="form-control" id="title" value=""
                placeholder="Title" />
            <input type="hidden" name="individual_id" id="individual_id" />
            <input type="hidden" name="business_id" id="business_id" />


            <span class="error error_title"></span>
        </div>

        <div class="col-md-6">
            <label><strong>Category </strong> <span class="text-danger">*</span></label>
            <select name="followup_category" class="form-control form-select" id="followup_category">
                <option value="" selected disabled>Select Category</option>
                @forelse ($followup_category as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @empty
                    <option value="" selected disabled>category not found</option>
                @endforelse

            </select>
            <span class="error error_followup_category"></span>
        </div>

    </div>

    <div class="form-group row mt-1">
        <div class="col-md-6">
            <label><strong>Date </strong> <span class="text-danger">*</span></label>
            <input required type="text" name="date" class="form-control" id="followup_date"
                value="{{ date('Y-m-d') }}" autocomplete="off" placeholder="Date" />
            <span class="error error_date"></span>
        </div>

        <div class="col-md-6">
            <label><strong>Status </strong> </label>
            <select name="status" class="form-control form-select" id="status">
                <option value="" selected disabled>Select Status</option>
                <option value="Interested">Interested </option>
                <option value="Pending">Pending</option>
                <option value="Not Connect">Not Connect</option>
                <option value="Not Interested">Not Interested</option>
            </select>
        </div>

    </div>

    <div class="form-group row mt-1">
        <div class="col-md-6">
            <label><strong>File </strong> </label>
            <input type="file" name="file" class="form-control" id="file" value="" />
        </div>

        <div class="col-md-6">
            <label><strong>Followup Type </strong> </label>
            <select name="followup_type" class="form-control form-select" id="followup_type">
                <option value="" selected disabled>Select Type</option>
                <option value="Call">Call</option>
                <option value="Chats">Chats</option>
                <option value="Mail">Mail</option>
            </select>
        </div>

    </div>

    <div class="form-group row mt-1">
        <div class="col-md-12">
            <label><strong>Details </strong></label>
            <textarea name="description" class="form-control ckEditor" id="description" cols="30" rows="4"
                placeholder="Details"></textarea>
        </div>
    </div>
    <div class="form-group row mt-3">
        <div class="col-md-12 d-flex justify-content-end">
            <div class="loading-btn-box">
                <button type="button" class="btn btn-sm loading_button display-none"><i
                        class="fas fa-spinner"></i></button>
                <button type="submit"
                    class="btn btn-sm btn-success float-end submit_button">@lang('menu.save')</button>
                <button type="reset" data-bs-dismiss="modal"
                    class="btn btn-sm btn-danger float-end me-2">@lang('menu.close')</button>
            </div>
        </div>
    </div>
</form>

<script>
    $('#followup_form').on('submit', function(e) {
        e.preventDefault();
        $('.loading_button').show();
        var url = $(this).attr('action');
        var leads_user_id = $("#leads_user_id").val();

        if ($("#leads_individual_or_business").val() == 'individual') {
            $("#individual_id").val(leads_user_id);
            $("#business_id").val("");
        } else if ($("#leads_individual_or_business").val() == 'business') {
            $("#individual_id").val("");
            $("#business_id").val(leads_user_id);
        }

        $.ajax({
            url: url,
            type: 'post',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                $('.loading_button').hide();
                toastr.success(data);
                $('#addModal').modal('hide');
                $('#followup_form')[0].reset();
                $('.followup_table').DataTable().ajax.reload();
            },
            error: function(err) {
                $('.loading_button').hide();
                $('.error').html('');
                if (err.status == 0) {
                    toastr.error('Net Connetion Error. Reload This Page.');
                    return;
                } else if (err.status == 500) {
                    toastr.error('Server error. Please contact to the support team.');
                    return;
                }
                $.each(err.responseJSON.errors, function(key, error) {
                    $('.error_e_' + key + '').html(error[0]);
                });
            }
        });
    });


    new Litepicker({
        singleMode: true,
        element: document.getElementById('followup_date'),
        dropdowns: {
            minYear: new Date().getFullYear() - 2,
            maxYear: new Date().getFullYear() + 10,
            months: true,
            years: true
        },
        tooltipText: {
            one: 'night',
            other: 'nights'
        },
        tooltipNumber: (totalDays) => {
            return totalDays - 1;
        },
        format: 'YYYY-MM-DD'
    });
</script>
