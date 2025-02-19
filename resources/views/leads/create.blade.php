<style>
    table tr td {
        font-size: 14px
    }

    .form-select {
        height: 30px;
        padding: 2px 10px;
        margin: 4px;
    }

    .accordion-button {
        font-size: 12px !important;
    }

    .accordion-item {
        border-radius: 0px;
    }

    .accordion-item:first-of-type .accordion-button {
        border-radius: 0px;
    }

    .accordion-button:focus {
        border: 0px;
        box-shadow: none;
    }

    input {
        margin: 4px;
    }

    .col-form {
        padding: 3px;
    }

    .row {
        padding: 6px
    }
</style>

{{ Form::open(array('url' => 'leads', 'method' => 'POST', 'id' => 'lead-creating-form')) }}
<div class="modal-body pt-0" style="height: 80vh;" >
    <div class="lead-content my-2" style="max-height: 100%; overflow-y: scroll;">
        <div class="card-body px-2 py-0">
            {{-- Details Pill Start --}}
            <div class="accordion" id="accordionPanelsStayOpenExample">
                <!-- Open Accordion Item -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="panelsStayOpen-headinginfo">
                        <button class="accordion-button p-2" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseinfo">
                            {{ __('LEAD INFORMATION') }}
                        </button>
                    </h2>

                    <div id="panelsStayOpen-collapseinfo" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headinginfo">
                        <div class="accordion-body">

                            <div class="mt-1" style="margin-left: 10px; width: 65%;">

                                <table class="w-100">
                                    <tbody>
                                        <tr>
                                            <td class="" style="width: 100px; font-size: 13px;">
                                                {{ __('Name') }}

                                                <span class="text-danger">*</span>
                                            </td>
                                            <td class="d-flex gap-1 mb-1" style="padding-left: 10px; font-size: 13px;">
                                                <input type="text" class="form-control d-none" placeholder="Prefix" name="lead_prefix">
                                                <input type="text" class="form form-control" placeholder="First Name" name="lead_first_name" required>
                                                <input type="text" class="form form-control" placeholder="Last Name" name="lead_last_name" required>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="" style="width: 100px; font-size: 13px;">
                                                {{ __('Lead Status') }}
                                            </td>
                                            <td class="" style="padding-left: 10px; font-size: 13px;">
                                                <select class="form-control select2" id="choices-1" name="lead_stage">
                                                    @foreach($stages as $key => $stage)
                                                    <option value="{{$key}}" {{ $key == 1 ? 'selected' : ''}}>{{$stage}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="" style="width: 100px; font-size: 13px;">
                                                {{ __('Brand') }}
                                            </td>
                                            <td class="" style="padding-left: 10px; font-size: 13px;">
                                                <select class="form-control select2 brand_id" id="choices-1011" name="brand_id">
                                                    <option value="" >Select Brand</option>
                                                    @foreach($companies as $key => $company)
                                                    <option value="{{$key}}" {{ $key == 1 ? 'selected' : ''}}>{{$company}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="" style="width: 100px; font-size: 13px;">
                                                {{ __('Location') }}
                                            </td>
                                            <td class="" style="padding-left: 10px; font-size: 13px;" id="branch_div">
                                                <select class="form-control select2" id="choice-3" name="lead_branch">
                                                    <option selected>Select Branch</option>
                                                </select>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="" style="width: 110px; font-size: 13px;">
                                                {{ __('User Responsible') }}
                                            </td>
                                            <td class="" style="padding-left: 10px; font-size: 13px;" id="assign_to_div">
                                                <select class="form-control select2" id="choice-2" name="lead_assgigned_user">
                                                    <option value=""> Select User</option>
                                                </select>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="" style="width: 100px; font-size: 13px;">
                                                {{ __('Agency') }}
                                            </td>
                                            <td class="" style="padding-left: 10px; font-size: 13px;">
                                                <select class="form-control select2" id="choice-7" name="lead_organization">
                                                    <option value="">Select Agency</option>
                                                    @foreach($organizations as $key => $org)
                                                    <option value="{{$key}}">{{$org}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>


                                        {{-- <tr>
                                                    <td class=""
                                                        style="width: 100px; font-size: 13px;">
                                                        {{ __('Agency Link') }}
                                        </td>
                                        <td class="" style="padding-left: 10px; font-size: 13px;">
                                            <select class="form-select w-50" name="lead_organization_link">
                                                <option>Select Agency Link</option>
                                                <option value="1">One</option>
                                                <option value="2">Two</option>
                                                <option value="3">Three</option>
                                            </select>
                                        </td>
                                        </tr> --}}
                                        <tr>
                                            <td class="" style="width: 100px; font-size: 13px;">
                                                {{ __('Lead Source') }}
                                            </td>
                                            <td class="" style="padding-left: 10px; font-size: 13px;">
                                                <select class="form-control select2" id="choice-4" name="lead_source">
                                                    <option value="">Select source</option>
                                                    @foreach($sources as $key => $source)
                                                    <option value="{{$key}}">{{$source}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="" style="width: 100px; font-size: 13px;">
                                                {{ __('Drive Link') }}
                                            </td>
                                            <td class="" style="padding-left: 10px; font-size: 13px;">
                                                <input type="text" class="form form-control" name="drive_link" value="">
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="panelsStayOpen-headingcust">
                        <button class="accordion-button p-2" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapsecust">
                            {{ __('CUSTOMER INFORMATION') }}
                        </button>
                    </h2>
                    <div id="panelsStayOpen-collapsecust" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingcust">
                        <div class="accordion-body">
                            <!-- Accordion Content -->
                            <div class="mt-1" style="margin-left: 10px; width: 65%;">

                                <table class="w-100">
                                    <tbody>
                                        <tr>
                                            <td class="" style="width: 100px; font-size: 13px;">
                                                {{ __('Email Address') }}

                                                <span class="text-danger">*</span>
                                            </td>
                                            <td class="" style="padding-left: 10px; font-size: 13px;">
                                                <input type="email" class="form-control" name="lead_email" required>
                                            </td>
                                        </tr>

                                        <tr class="d-none">
                                            <td class="" style="width: 153px; font-size: 13px;">
                                                {{ __('Email Address (Referrer)') }}
                                            </td>
                                            <td class="" style="padding-left: 10px; font-size: 13px;">
                                                <input type="email" class="form-control" name="referrer_email">
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="" style="width: 100px; font-size: 13px;">
                                                {{ __('Mobile Phone') }}

                                                <span class="text-danger">*</span>
                                            </td>
                                            <td class="" style="padding-left: 10px; font-size: 13px;">
                                                <input type="text" class="form-control" name="lead_phone" required>
                                            </td>
                                        </tr>

                                        <tr class="d-none">
                                            <td class="" style="width: 100px; font-size: 13px;">
                                                {{ __('Mobile Phone') }}

                                            </td>
                                            <td class="" style="padding-left: 10px; font-size: 13px;">
                                                <input type="text" class="form-control" name="lead_mobile_phone">

                                            </td>
                                        </tr>


                                        {{-- <tr>
                                            <td class="" style="width: 130px; font-size: 13px;">
                                                {{ __('Email Opted Out') }}
                                            </td>
                                            <td class="" style="padding-left: 10px; font-size: 13px;">
                                                <input type="checkbox" name="" id="" class="ms-2">
                                            </td>
                                        </tr> --}}
                                    </tbody>
                                </table>
                            </div>


                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="panelsStayOpen-headingaddress">
                        <button class="accordion-button p-2" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseaddress">
                            {{ __('CUSTOMER ADDRESS') }}
                        </button>
                    </h2>
                    <div id="panelsStayOpen-collapseaddress" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingaddress">
                        <div class="accordion-body">
                            <div class="mt-1" style="margin-left: 10px; width: 65%;">

                                <table class="w-100">
                                    <tbody>
                                        <tr>
                                            <td class="" style="width: 115PX; font-size: 13px;padding-right: 20px;">
                                                Address
                                            </td>
                                            <td class="" style="width: 350PX; font-size: 13px; bg-danger">
                                                <div class="form-floating">
                                                    <textarea class="form-control" placeholder="Street" id="floatingTextarea" name="lead_street"></textarea>
                                                </div>
                                                <div class="row">
                                                    <div class="col-6 col-form">
                                                        <input type="text" class="form-control" id="formGroupExampleInput" placeholder="City" name="lead_city">
                                                    </div>
                                                    <div class="col-6 col-form">
                                                        <input type="text" class="form-control" id="formGroupExampleInput" placeholder="State/Province" name="lead_state">
                                                    </div>
                                                    <div class="col-6 col-form">
                                                        <input type="text" class="form-control" id="formGroupExampleInput" placeholder="Postel Code" name="lead_postal_code">
                                                    </div>
                                                    <div class="col-6 col-form" style="text-align: left;">
                                                        <select class="form-control select2" id="choice-5" name="lead_country">
                                                            <option>Country...</option>
                                                            @foreach($countries as $con)
                                                            <option value="{{$con}}">{{$con}}</option>
                                                            @endforeach

                                                        </select>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="panelsStayOpen-headingkeynote">
                        <button class="accordion-button p-2" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapsekeynote">
                            {{ __('KEYNOTE') }}
                        </button>
                    </h2>
                    <div id="panelsStayOpen-collapsekeynote" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingkeynote">
                        <div class="accordion-body">
                            <div class="mt-1" style="margin-left: 10px; width: 65%;">
                                <table class="w-100">
                                    <tbody>
                                        <tr>
                                            <td class="" style="width: 100px;  font-size: 13px;">
                                                Description
                                            </td>
                                            <td style="width: 374px; text-align: right; font-size: 13px;">
                                                <div class="" style="margin-left: 14px;">
                                                    <textarea class="form-control" rows="4" placeholder="description" name="lead_description"></textarea>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="panelsStayOpen-headingkeytag">
                        <button class="accordion-button p-2" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapsekeytag">
                            {{ __('TAG LIST') }}
                        </button>
                    </h2>
                    <div id="panelsStayOpen-collapsekeytag" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingkeytag">
                        <div class="accordion-body">
                            <div class="mt-1" style="margin-left: 10px; width: 65%;">

                                <table class="w-100">
                                    <tbody>
                                        <tr>
                                            <td class="" style="width: 100px; font-size: 13px;">
                                                {{ __('Tag List') }}
                                            </td>
                                            <td class="" style="padding-left: 10px;">
                                                <select name="lead_tags_list" id="choice-6" class="form form-control select2">
                                                    <option value=""></option>
                                                    <option value="Public">Public</option>
                                                    <option value="Private">Private</option>
                                                </select>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">

    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn  btn-dark new-lead-btn">

</div>

{{Form::close()}}

<script>

    $(".brand_id").on("change", function(){
        var id = $(this).val();

        $.ajax({
            type: 'GET',
            url: '{{ route('lead_companyemployees') }}',
            data: {
                id: id  // Add a key for the id parameter
            },
            success: function(data){
                data = JSON.parse(data);

                if (data.status === 'success') {
                    $("#assign_to_div").html(data.employees);
                    select2();
                    $("#branch_div").html(data.branches);
                    select2(); // Assuming this is a function to initialize or update a select2 dropdown
                } else {
                    console.error('Server returned an error:', data.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX request failed:', status, error);
            }
        });
    });

</script>
