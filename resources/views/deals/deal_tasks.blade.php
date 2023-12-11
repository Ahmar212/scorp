@extends('layouts.admin')
<?php $setting = \App\Models\Utility::colorset(); ?>
{{-- <link rel="stylesheet" href="{{ asset('css/customsidebar.css') }}"> --}}


@section('page-title')
{{ __('Tasks') }}
@endsection

@push('css-page')
<link rel="stylesheet" href="{{ asset('css/summernote/summernote-bs4.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/plugins/dragula.min.css') }}" id="main-style-link">
@endpush


@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
<li class="breadcrumb-item">{{ __('Tasks') }}</li>
@endsection

@push('css-page')
<link rel="stylesheet" href="{{ asset('assets/js/drag-resize-columns/dist/jquery.resizableColumns.css') }}">
@endpush


@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">


            <div class="card-body">

                <div class="row align-items-center ps-0 ms-0 pe-4 my-2">
                    <div class="col-2">
                        <p class="mb-0 pb-0">Tasks</p>
                        <div class="dropdown">
                            <button class="dropdown-toggle All-leads" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                ALL Tasks
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <li><a class="dropdown-item assigned_to" href="javascript:void(0)">Assigned to</a></li>
                                <li><a class="dropdown-item update-status-modal" href="javascript:void(0)">Update Status</a></li>
                                <li><a class="dropdown-item" href="#">Brand Change</a></li>
                                <li><a class="dropdown-item delete-bulk-tasks" href="javascript:void(0)">Delete</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-10 d-flex justify-content-end gap-2">
                        <div class="input-group w-25">
                            <button class="btn btn-sm list-global-search-btn">
                                <span class="input-group-text bg-transparent border-0  px-2 py-1" id="basic-addon1">
                                    <i class="ti ti-search" style="font-size: 18px"></i>
                                </span>
                            </button>
                            <input type="Search" class="form-control border-0 bg-transparent ps-0 list-global-search" placeholder="Search this list..." aria-label="Username" aria-describedby="basic-addon1">
                        </div>

                        <button class="btn px-2 pb-2 pt-2 refresh-list" style="background-color: #b5282f; color:white;"><i class="ti ti-refresh" style="font-size: 18px"></i></button>

                        <button class="btn filter-btn-show p-2" style="background-color: #b5282f; color:white;" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ti ti-filter" style="font-size:18px"></i>
                        </button>

                        @can('create task')
                        <button data-size="lg" data-url="{{ route('organiation.tasks.create', 1) }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{ __('Create Task') }}" class="btn btn-sm p-2 btn-primary">
                            <i class="ti ti-plus" style="font-size:18px"></i>
                        </button>
                        @endcan

                        {{-- <a data-size="lg" data-url="{{ route('organiation.tasks.create', 1) }}" data-ajax-popup="true" data-bs-toggle="tooltip" class="btn btn-sm text-white" style="background-color: #b5282f">
                        <i class="ti ti-plus"></i>
                        </a> --}}
                    </div>
                </div>

                <div class="filter-data px-3" id="filter-show" <?= isset($_GET) && !empty($_GET) ? '' : 'style="display: none;"' ?><?= isset($_GET) && !empty($_GET) ? '' : 'style="display: none;"' ?><?= isset($_GET) && !empty($_GET) ? '' : 'style="display: none;"' ?>>
                    <form action="/deals/get-user-tasks" method="GET" class="">

                        <div class="row my-3">
                            <div class="col-md-4 mt-2">
                                <label for="">Due Date</label>
                                <input type="date" class="form form-control" name="due_date" value="<?= isset($_GET['due_date']) ? $_GET['due_date'] : '' ?>" style="width: 95%; border-color:#aaa">
                            </div>


                            <div class="col-md-4"> <label for="">Subject</label>
                                <select class="form form-control select2" id="choices-multiple110" name="subjects[]" multiple style="width: 95%;">
                                    <option value="">Select Subject</option>
                                    @foreach ($tasks_for_filter as $filter_task)
                                    <option value="{{ $filter_task->name }}" <?= isset($_GET['subjects']) && in_array($filter_task->name, $_GET['subjects']) ? 'selected' : '' ?> class="">{{ $filter_task->name }}</option>
                                    @endforeach
                                </select>
                            </div>



                            <div class="col-md-4"> <label for="">Assigned To</label>
                                <select name="assigned_to[]" id="choices-multiple333" class="form form-control select2" multiple style="width: 95%;">
                                    <option value="">Select user</option>
                                    @foreach ($users as $key => $user)
                                    <option value="{{ $key }}" <?= isset($_GET['assigned_to']) && in_array($key, $_GET['assigned_to']) ? 'selected' : '' ?> class="">{{ $user }}</option>
                                    @endforeach
                                </select>
                            </div>



                            <div class="col-md-4"> <label for="">Company/Brand</label>
                                <select class="form form-control select2" id="choices-multiple444" name="brands[]" multiple style="width: 95%;">
                                    <option value="">Select Brand</option>
                                    @foreach ($brands as $key => $brand)
                                    <option value="{{ $key }}" <?= isset($_GET['brands']) && in_array($key, $_GET['brands']) ? 'selected' : '' ?> class="">{{ $brand }}</option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="col-md-4 mt-4">
                                <input type="submit" class="btn form-btn me-2" style="background-color: #b5282f; color:white;">
                                <a href="/deals/get-user-tasks" class="btn form-btn" style="background-color: #b5282f;color:white;">Reset</a>
                            </div>
                        </div>
                        <div class="row my-4">
                            <div class="enries_per_page" style="max-width: 300px; display: flex;">

                                <?php
                                $all_params = isset($_GET) ? $_GET : '';
                                if (isset($all_params['num_results_on_page'])) {
                                    unset($all_params['num_results_on_page']);
                                }
                                ?>
                                <input type="hidden" value="<?= http_build_query($all_params) ?>" class="url_params">
                                <select name="" id="" class="enteries_per_page form form-control" style="width: 100px; margin-right: 1rem;">
                                    <option <?= isset($_GET['num_results_on_page']) && $_GET['num_results_on_page'] == 25 ? 'selected' : '' ?> value="25">25</option>
                                    <option <?= isset($_GET['num_results_on_page']) && $_GET['num_results_on_page'] == 100 ? 'selected' : '' ?> value="100">100</option>
                                    <option <?= isset($_GET['num_results_on_page']) && $_GET['num_results_on_page'] == 300 ? 'selected' : '' ?> value="300">300</option>
                                    <option <?= isset($_GET['num_results_on_page']) && $_GET['num_results_on_page'] == 1000 ? 'selected' : '' ?> value="1000">1000</option>
                                    <option <?= isset($_GET['num_results_on_page']) && $_GET['num_results_on_page'] == $total_records ? 'selected' : '' ?> value="{{ $total_records }}">all</option>
                                </select>

                                <span style="margin-top: 5px;">entries per page</span>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="table-responsive mt-3">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th style="width: 50px !important;">
                                    <input type="checkbox" class="main-check">
                                </th>
                                <th>{{ __('Due Date') }}</th>
                                <th>{{ __('Subject') }}</th>
                                <th>{{ __('Assigned To') }}</th>
                                <th>{{ __('Company/Team') }}</th>
                                <th>{{ __('Status') }}</th>
                            </tr>
                        </thead>
                        <tbody class="tasks_tbody">
                            @forelse($tasks as $key => $task)
                            @php

                            $due_date = strtotime($task->due_date);
                            $current_date = strtotime(date('Y-m-d'));

                            if ($due_date < $current_date && strtolower($task->status) == 0) {
                                $color_code = 'bg-danger-scorp';
                                }elseif (strtolower($task->status) == 1) {
                                $color_code = 'bg-success-scorp';
                                }
                                elseif ($due_date == $current_date && strtolower($task->status) == 0) {
                                $color_code = 'bg-warning-scorp';
                                }else {
                                $color_code = 'bg-secondary-scorp';
                                }

                                @endphp
                                <tr>
                                    <td>
                                        <input type="checkbox" name="tasks[]" value="{{$task->id}}" class="sub-check">
                                    </td>
                                    <td> <span class="badge {{ $color_code }} text-white">{{ $task->due_date }}</span>
                                    </td>
                                    <td>
                                        <span style="cursor:pointer" class="task-name hyper-link" @can('view task') onclick="openNav(<?= $task->id ?>)" @endcan data-task-id="{{ $task->id }}">{{ $task->name }}</span>
                                    </td>
                                    <td>
                                        @if (!empty($task->assigned_to))
                                        <span style="cursor:pointer" class="hyper-link" onclick="openSidebar('/users/'+{{ $task->assigned_to }}+'/user_detail')">
                                            {{ $users[$task->assigned_to] }}
                                        </span>
                                        @endif
                                    </td>

                                    <td>

                                        @if (!empty($task->assigned_to))
                                        @if ($task->assigned_type == 'company')
                                        <span style="cursor:pointer" class="hyper-link" onclick="openSidebar('/users/'+{{ $task->assigned_to }}+'/user_detail')">
                                            {{ $users[$task->assigned_to] }}
                                        </span>
                                        @else
                                        <?php
                                        $assigned_user = \App\Models\User::findOrFail($task->assigned_to);
                                        ?>

                                        <span style="cursor:pointer" class="hyper-link" onclick="openSidebar('/users/'+{{ $assigned_user->created_by }}+'/user_detail')">
                                            {{ isset($users[$assigned_user->created_by]) ? $users[$assigned_user->created_by] : '' }}
                                        </span>
                                        @endif
                                        @endif
                                    </td>

                                    <td>
                                        @if ($task->status == 1)
                                        <span class="badge {{ $color_code }} text-white">{{ __('Completed') }}</span>
                                        @else
                                        <span class="badge {{ $color_code }} text-white">{{ __('On Going') }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($total_records > 0)
                @include('layouts.pagination', [
                'total_pages' => $total_records,
                'num_results_on_page' => 50,
                ])
                @endif


                <div id="mySidenav" style="z-index: 1065; padding-left:10px; box-shadow: -5px 0px 30px 0px #aaa;" class="sidenav <?= isset($setting['cust_darklayout']) && $setting['cust_darklayout'] == 'on' ? 'sidenav-dark' : 'sidenav-light' ?>" style="padding-left: 5px">
                </div>

            </div>
        </div>
    </div>
</div>
@endsection



<div class="modal" id="update-status-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Status</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('update-bulk-task-status') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <select name="status" id="bulk_status" class="form form-control">
                        <option value="">Select Status</option>
                        <option value="0">On Going</option>
                        <option value="1">Completed</option>
                    </select>

                    <input type="hidden" class="task_ids" value="" name="task_ids">
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary" value="Update Status">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>


@push('script-page')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<script>
    $(document).ready(function() {
        $('.filter-btn-show').click(function() {
            $("#filter-show").toggle();
        });
    });

    $(document).on("click", ".list-global-search-btn", function() {
        var search = $(".list-global-search").val();
        var ajaxCall = 'true';
        $(".tasks_tbody").html('Loading...');

        $.ajax({
            type: 'GET',
            url: "/deals/get-user-tasks",
            data: {
                search: search,
                ajaxCall: ajaxCall
            },
            success: function(data) {
                data = JSON.parse(data);

                if (data.status == 'success') {
                    console.log(data.html);
                    $(".tasks_tbody").html(data.html);
                }
            }
        })
    })

    $(".refresh-list").on("click", function() {
        var ajaxCall = 'true';
        $(".tasks_tbody").html('Loading...');

        $.ajax({
            type: 'GET',
            url: "/deals/get-user-tasks",
            data: {
                ajaxCall: ajaxCall
            },
            success: function(data) {
                data = JSON.parse(data);

                if (data.status == 'success') {
                    $(".tasks_tbody").html(data.html);
                }
            }
        });
    })


    /* Set the width of the side navigation to 250px and the left margin of the page content to 250px and add a black background color to body */
    function openNav(task_id) {
        var ww = $(window).width()

        $.ajax({
            type: 'GET',
            url: "{{ route('get-task-detail') }}",
            data: {
                task_id: task_id
            },
            success: function(data) {
                data = JSON.parse(data);
                if (data.status == 'success') {
                    $("#mySidenav").html(data.html);
                    $(".block-screen").css('display', 'none');
                }
            }
        });


        if (ww < 500) {
            $("#mySidenav").css('width', ww + 'px');
            $("#main").css('margin-right', ww + 'px');
        } else {
            $("#mySidenav").css('width', '850px');;
            $("#main").css('margin-right', "850px");
        }

        $("#modal-discussion-add").attr('data-org-id', task_id);
        $('.modal-discussion-add-span').removeClass('ti-minus');
        $('.modal-discussion-add-span').addClass('ti-plus');
        $(".add-discussion-div").addClass('d-none');
        $(".block-screen").css('display', 'block');
        $("#body").css('overflow', 'hidden');

        // var csrf_token = $('meta[name="csrf-token"]').attr('content');

        // $.ajax({
        //     url: "/leads/getDiscussions",
        //     data: {
        //         lead_id,
        //         _token: csrf_token,
        //     },
        //     type: "POST",
        //     cache: false,
        //     success: function(data) {
        //         data = JSON.parse(data);
        //         //console.log(data);

        //         if (data.status) {
        //             $(".discussion-list-group").html(data.content);
        //             $(".lead_id").val(lead_id);


        //         }
        //     }
        // });

    }

    /* Set the width of the side navigation to 0 and the left margin of the page content to 0, and the background color of body to white */
    function closeNav() {
        $("#mySidenav").css("width", '0');
        $("#main").css("margin-right", '0');
        $("#modal-discussion-add").removeAttr('data-org-id');
        $('.modal-discussion-add-span').removeClass('ti-minus');
        $('.modal-discussion-add-span').addClass('ti-plus');
        $(".add-discussion-div").addClass('d-none');
        $(".block-screen").css('display', 'none');
        $("#body").css('overflow', 'visible');
    }

    $(document).on("click", ".edit-input", function() {
        var value = $(this).val();
        var name = $(this).attr('name');
        var id = $(".task-id").val();
        //var org_did = $(".org_did").val();

        $.ajax({
            type: 'GET',
            url: "/tasks/get-field/" + id,
            data: {
                name,
                id
            },
            success: function(data) {
                data = JSON.parse(data);
                if (data.status == 'success') {
                    $('.' + name + '-td').html(data.html);
                }
            }
        })

    })


    $(document).on("click", ".edit-btn-data", function() {
        var name = $(this).attr('data-name');
        var id = $(".task-id").val();
        var value = $('.' + name).val();


        $.ajax({
            type: 'GET',
            url: "/tasks/" + id + "/update-data",
            data: {
                value: value,
                name: name,
                id: id
            },
            success: function(data) {
                data = JSON.parse(data);

                if (data.status == 'success') {
                    show_toastr('Success', data.message, 'msg');
                    $('.' + name + '-td').html(data.html);
                }
            }
        })

    });

    function DeleteComment(id, taskID) {
        $('#dellhover').show();
        $.ajax({
            type: "GET",
            url: "{{ url('delete/task/comment') }}" + '/' + id + '/' + taskID,
            success: function(data) {
                data = JSON.parse(data);

                console.log(data);

                if (data.status == 'success') {
                    show_toastr('Success', data.message, 'success');
                    $('#commonModal').modal('hide');
                    $('.list-group-flush').html(data.html);
                    // openNav(data.lead.id);
                    // return false;
                } else {
                    show_toastr('Error', data.message, 'error');
                    $(".create-discussion-btn").val('Create');
                    $('.create-discussion-btn').removeAttr('disabled');
                }
            }
        });
    }

    $(document).on("submit", "#taskDiscussion", function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        var id = $('.task-id').val();

        $(".create-discussion-btn").val('Processing...');
        $('.create-discussion-btn').attr('disabled', 'disabled');

        $.ajax({
            type: "POST",
            url: "/tasks/" + id + "/discussions",
            data: formData,
            success: function(data) {
                data = JSON.parse(data);

                console.log(data);

                if (data.status == 'success') {
                    show_toastr('Success', data.message, 'success');
                    $('#commonModal').modal('hide');
                    $('.list-group-flush').html(data.html);
                    // openNav(data.lead.id);
                    // return false;
                } else {
                    show_toastr('Error', data.message, 'error');
                    $(".create-discussion-btn").val('Create');
                    $('.create-discussion-btn').removeAttr('disabled');
                }
            }
        });
    })


    $(document).on("change", ".assigned_to", function() {

        var val = $(this).val();
        var userType = <?= json_encode($user_type) ?>;

        if (userType[val] == 'company' || userType[val] == 'team') {
            $(".assigned_to_type").removeClass('d-none');
        } else {
            $(".assigned_to_type").addClass('d-none');
        }
    });

    $(document).on('change', '.main-check', function() {
        $(".sub-check").prop('checked', $(this).prop('checked'));
    });

    $(document).on("click", ".update-status-modal", function() {
        // Get an array of selected checkbox IDs
        var selectedIds = $('.sub-check:checked').map(function() {
            return this.value;
        }).get();

        // Set the value of the 'task_ids' input field
        $('.task_ids').val(selectedIds.join(','));

        // Optionally, you can also get the selected status
        var selectedStatus = $('#bulk_status').val();
        $("#update-status-modal").modal('show');
    });

    $(document).on("click", '.delete-bulk-tasks', function() {
        var task_ids = $(".sub-check:checked");
        var selectedIds = $('.sub-check:checked').map(function() {
            return this.value;
        }).get();

        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '/delete-bulk-tasks?ids='+selectedIds.join(',');
            }
        });
    })
</script>
@endpush