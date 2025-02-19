@extends('layouts.admin')
<?php $setting = \App\Models\Utility::colorset(); ?>
{{-- <link rel="stylesheet" href="{{ asset('css/customsidebar.css') }}"> --}}


@section('page-title')
    {{ __('Manage Deals') }} @if ($pipeline)
        - {{ $pipeline->name }}
    @endif
@endsection

@push('css-page')
    <link rel="stylesheet" href="{{ asset('css/summernote/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/dragula.min.css') }}" id="main-style-link">
@endpush
@push('script-page')
    <script src="{{ asset('css/summernote/summernote-bs4.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/dragula.min.js') }}"></script>
    <script>
        ! function(a) {
            "use strict";
            var t = function() {
                this.$body = a("body")
            };
            t.prototype.init = function() {
                a('[data-plugin="dragula"]').each(function() {
                    var t = a(this).data("containers"),
                        n = [];
                    if (t)
                        for (var i = 0; i < t.length; i++) n.push(a("#" + t[i])[0]);
                    else n = [a(this)[0]];
                    var r = a(this).data("handleclass");
                    r ? dragula(n, {
                        moves: function(a, t, n) {
                            return n.classList.contains(r)
                        }
                    }) : dragula(n).on('drop', function(el, target, source, sibling) {

                        var order = [];
                        $("#" + target.id + " > div").each(function() {
                            order[$(this).index()] = $(this).attr('data-id');
                        });

                        var id = $(el).attr('data-id');

                        var old_status = $("#" + source.id).data('status');
                        var new_status = $("#" + target.id).data('status');
                        var stage_id = $(target).attr('data-id');
                        var pipeline_id = '{{ $pipeline->id }}';

                        $("#" + source.id).parent().find('.count').text($("#" + source.id + " > div")
                            .length);
                        $("#" + target.id).parent().find('.count').text($("#" + target.id + " > div")
                            .length);
                        $.ajax({
                            url: '{{ route('deals.order') }}',
                            type: 'POST',
                            data: {
                                deal_id: id,
                                stage_id: stage_id,
                                order: order,
                                new_status: new_status,
                                old_status: old_status,
                                pipeline_id: pipeline_id,
                                "_token": $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(data) {},
                            error: function(data) {
                                data = data.responseJSON;
                                show_toastr('error', data.error, 'error')
                            }
                        });
                    });
                })
            }, a.Dragula = new t, a.Dragula.Constructor = t
        }(window.jQuery),
        function(a) {
            "use strict";

            a.Dragula.init()

        }(window.jQuery);
    </script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.9/jquery.lazy.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.9/jquery.lazy.plugins.min.js">
    </script>

    <script>
        $(".lazy").Lazy({
            beforeLoad: function(element) {
                // called before an elements gets handled
                alert('Before load');
            },
            afterLoad: function(element) {
                // called after an element was successfully handled
                alert('after load');
            },
            onError: function(element) {
                // called whenever an element could not be handled
                alert('error');
            },
            onFinishedAll: function() {
                // called once all elements was handled
                alert('finished');
            }
        });

        $(document).on("change", ".change-pipeline select[name=default_pipeline_id]", function() {
            $('#change-pipeline').submit();
        });

        $(document).on("click", ".btn_submit", function() {
            var discussion = $(".discussion-msg").val();
            var deal_id = $(".deal_id").val();
            var csrf_token = $('meta[name="csrf-token"]').attr('content');

            if (discussion == '') {
                return false;
            }

            $.ajax({
                url: "/deals/saveDiscussions",
                data: {
                    deal_id,
                    discussion,
                    _token: csrf_token,
                },
                type: "POST",
                cache: false,
                success: function(data) {
                    data = JSON.parse(data);

                    if (data.status) {
                        $(".discussion-list-group").append(data.content);
                        $(".discussion-msg").val('');
                        $('.modal-discussion-add-span').removeClass('ti-minus');
                        $('.modal-discussion-add-span').addClass('ti-plus');
                        $(".add-discussion-div").addClass('d-none');
                        Swal.fire(
                            'Discussion Save!',
                            'Discussion saved successfully.',
                            'success'
                        );
                    }
                }
            });

        })

        $(document).on("click", "#modal-discussion-add", function() {

            if ($('.modal-discussion-add-span').hasClass('ti-plus')) {
                $('.modal-discussion-add-span').removeClass('ti-plus');
                $('.modal-discussion-add-span').addClass('ti-minus');
                $(".add-discussion-div").removeClass('d-none');
            } else {
                $('.modal-discussion-add-span').removeClass('ti-minus');
                $('.modal-discussion-add-span').addClass('ti-plus');
                $(".add-discussion-div").addClass('d-none');
            }
        })
    </script>
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Deal') }}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        {{ Form::open(['route' => 'deals.change.pipeline', 'id' => 'change-pipeline', 'class' => 'btn btn-sm ']) }}
        {{ Form::select('default_pipeline_id', $pipelines, $pipeline->id, ['class' => 'form-control select', 'id' => 'default_pipeline_id']) }}
        {{ Form::close() }}


        <a href="{{ route('deals.list') }}" data-size="lg" data-bs-toggle="tooltip" title="{{ __('List View') }}"
            class="btn btn-sm btn-dark">
            <i class="ti ti-list"></i>
        </a>
        <a href="#" data-size="lg" data-url="{{ route('deals.create') }}" data-ajax-popup="true"
            data-bs-toggle="tooltip" title="{{ __('Create New Lead') }}" class="btn btn-sm btn-dark">
            <i class="ti ti-plus"></i>
        </a>

        <button data-size="lg" data-bs-toggle="tooltip" title="{{ __('Import Csv') }}" class="btn btn-sm btn-dark"
            style="display: none;" id="import_csv_modal_btn" data-bs-toggle="modal" data-bs-target="#import_csv">
            <i class="fa fa-file-csv"></i>
        </button>


    </div>
@endsection





<div id="mySidenav" class="sidenav <?= $setting['cust_darklayout'] == 'on' ? 'sidenav-dark' : 'sidenav-light' ?>">
    <a href="javascript:void(0)" class="closebtn" onclick="closeSidbar()">&times;</a>

    <div class="d-flex justify-content-between px-3">
        <h5>Discussion</h5>
        <div class="d-flex">
            <a href="#" class="btn btn-sm btn-primary" id="modal-discussion-add">
                <i class="ti ti-plus modal-discussion-add-span"></i>
            </a>
        </div>
    </div>

    <ul class="discussion-list-group list-group list-group-flush mt-2" style="max-height: 400px; overflow-y: scroll;">

    </ul>

    <input type="hidden" name='deal_id' class='deal_id' />

    <div class="add-discussion-div d-none">
        <div class="form form-group">
            <label for="">Discussion</label>
            <textarea name="" id="" cols="30" rows="10" class="form form-control discussion-msg"></textarea>
        </div>

        <div class="form form-group">
            <input type="button" value="Submit" class="btn btn-dark btn-icon btn_submit">
        </div>
    </div>
</div>

@section('content')
    @can('show deals stats')
        <div class="row">
            <div class="col-sm-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center justify-content-between">
                            <div class="col-auto mb-3 mb-sm-0">
                                <small class="text-muted">{{ __('Total Deals') }}</small>
                                <h4 class="m-0">{{ $cnt_deal['total'] }}</h4>
                            </div>
                            <div class="col-auto">
                                <div class="theme-avtar bg-info">
                                    <i class="ti ti-layers-difference"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center justify-content-between">
                            <div class="col-auto mb-3 mb-sm-0">
                                <small class="text-muted">{{ __('This Month Total Deals') }}</small>
                                <h4 class="m-0">{{ $cnt_deal['this_month'] }}</h4>
                            </div>
                            <div class="col-auto">
                                <div class="theme-avtar bg-primary">
                                    <i class="ti ti-layers-difference"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center justify-content-between">
                            <div class="col-auto mb-3 mb-sm-0">
                                <small class="text-muted">{{ __('This Week Total Deals') }}</small>
                                <h4 class="m-0">{{ $cnt_deal['this_week'] }}</h4>
                            </div>
                            <div class="col-auto">
                                <div class="theme-avtar bg-warning">
                                    <i class="ti ti-layers-difference"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center justify-content-between">
                            <div class="col-auto mb-3 mb-sm-0">
                                <small class="text-muted">{{ __('Last 30 Days Total Deals') }}</small>
                                <h4 class="m-0">{{ $cnt_deal['last_30days'] }}</h4>
                            </div>
                            <div class="col-auto">
                                <div class="theme-avtar bg-danger">
                                    <i class="ti ti-layers-difference"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endcan
    <div class="row">

        <div class="col-sm-12">
            <div class="my-4 mx-4">
                <div class="enries_per_page" style="max-width: 300px; display: flex;">

                    <?php
                    $all_params = isset($_GET) ? $_GET : '';
                    if (isset($all_params['num_results_on_page'])) {
                        unset($all_params['num_results_on_page']);
                    }
                    ?>
                    <input type="hidden" value="<?= http_build_query($all_params) ?>" class="url_params">
                    <select name="" id="" class="enteries_per_page form form-control"
                        style="width: 100px; margin-right: 1rem;">
                        <option
                            <?= isset($_GET['num_results_on_page']) && $_GET['num_results_on_page'] == 25 ? 'selected' : '' ?>
                            value="25">25</option>
                        <option
                            <?= isset($_GET['num_results_on_page']) && $_GET['num_results_on_page'] == 100 ? 'selected' : '' ?>
                            value="100">100</option>
                        <option
                            <?= isset($_GET['num_results_on_page']) && $_GET['num_results_on_page'] == 300 ? 'selected' : '' ?>
                            value="300">300</option>
                        <option
                            <?= isset($_GET['num_results_on_page']) && $_GET['num_results_on_page'] == 1000 ? 'selected' : '' ?>
                            value="1000">1000</option>
                        <option
                            <?= isset($_GET['num_results_on_page']) && $_GET['num_results_on_page'] == $total_records ? 'selected' : '' ?>
                            value="{{ $total_records }}">all</option>
                    </select>

                    <span style="margin-top: 5px;">entries per page</span>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            @php
                $stages = $pipeline->stages;

                $json = [];
                foreach ($stages as $stage) {
                    $json[] = 'task-list-' . $stage->id;
                }
            @endphp
            <div class="row kanban-wrapper horizontal-scroll-cards" data-containers='{!! json_encode($json) !!}'
                data-plugin="dragula">
                @foreach ($stages as $stage)
                    @php($deals = $stage->deals())
                    <div class="col" style="width:250px;">
                        <div class="card">
                            <div class="card-header">
                                <div class="float-end">
                                    <span class="btn btn-sm btn-dark btn-icon count" style="font-size: 10px;">
                                        {{ $stage->deals_count() }}
                                    </span>
                                </div>
                                <h4 class="mb-0" style="font-size: 14px;">{{ $stage->name }}</h4>
                            </div>
                            <div class="card-body kanban-box" id="task-list-{{ $stage->id }}"
                                data-id="{{ $stage->id }}">
                                @foreach ($deals as $deal)
                                    <div class="card lazy" data-id="{{ $deal->id }}">
                                        <div class="pt-3 ps-3">
                                            @php($labels = $deal->labels())
                                            @if ($labels)
                                                @foreach ($labels as $label)
                                                    <div class="badge-xs badge bg-{{ $label->color }} p-2 px-3 rounded">
                                                        {{ $label->name }}</div>
                                                @endforeach
                                            @endif
                                        </div>
                                        <div class="card-header border-0 pb-0 position-relative">
                                            <h5><a href="@can('view deal')@if ($deal->is_active){{ route('deals.show', $deal->id) }}@else#@endif @else#@endcan"
                                                    style="font-size: 14px;">{{ $deal->name }}</a>
                                                <span style="cursor:pointer" onclick="openNav(<?= $deal->id ?>)"
                                                    data-deal-id="{{ $deal->id }}"
                                                    class="ti ti-brand-hipchat"></span>
                                            </h5>

                                            <div class="card-header-right">
                                                @if (Auth::user()->type != 'client')
                                                    <div class="btn-group card-option">
                                                        <button type="button" class="btn dropdown-toggle"
                                                            data-bs-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                            <i class="ti ti-dots-vertical"></i>
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            @can('edit deal')
                                                                <a href="#!" data-size="md"
                                                                    data-url="{{ URL::to('deals/' . $deal->id . '/labels') }}"
                                                                    data-ajax-popup="true" class="dropdown-item"
                                                                    data-bs-original-title="{{ __('Labels') }}">
                                                                    <i class="ti ti-bookmark"></i>
                                                                    <span>{{ __('Labels') }}</span>
                                                                </a>

                                                                <a href="#!" data-size="lg"
                                                                    data-url="{{ URL::to('deals/' . $deal->id . '/edit') }}"
                                                                    data-ajax-popup="true" class="dropdown-item"
                                                                    data-bs-original-title="{{ __('Edit Deal') }}">
                                                                    <i class="ti ti-pencil"></i>
                                                                    <span>{{ __('Edit') }}</span>
                                                                </a>
                                                            @endcan
                                                            @can('delete deal')
                                                                {!! Form::open([
                                                                    'method' => 'DELETE',
                                                                    'route' => ['deals.destroy', $deal->id],
                                                                    'id' => 'delete-form-' . $deal->id,
                                                                ]) !!}
                                                                <a href="#!" class="dropdown-item bs-pass-para">
                                                                    <i class="ti ti-archive"></i>
                                                                    <span> {{ __('Delete') }} </span>
                                                                </a>
                                                                {!! Form::close() !!}
                                                            @endcan


                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <?php
                                        $products = $deal->products();
                                        $sources = $deal->sources();
                                        ?>
                                        <div class="card-body">
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <ul class="list-inline mb-0">
                                                    <li class="list-inline-item d-inline-flex align-items-center"
                                                        data-bs-toggle="tooltip" title="{{ __('Tasks') }}">
                                                        <i class="f-16 text-primary ti ti-list"></i>
                                                        {{ count($deal->tasks) }}/{{ count($deal->complete_tasks) }}
                                                    </li>
                                                </ul>
                                                <div class="user-group">
                                                    <i class="text-primary ti ti-report-money"></i>
                                                    {{ \Auth::user()->priceFormat($deal->price) }}
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <ul class="list-inline mb-0">

                                                    <li class="list-inline-item d-inline-flex align-items-center"
                                                        data-bs-toggle="tooltip" title="{{ __('Product') }}">
                                                        <i class="f-16 text-primary ti ti-shopping-cart"></i>
                                                        {{ count($products) }}
                                                    </li>

                                                    <li class="list-inline-item d-inline-flex align-items-center"
                                                        data-bs-toggle="tooltip" title="{{ __('Source') }}">
                                                        <i
                                                            class="f-16 text-primary ti ti-social"></i>{{ count($sources) }}
                                                    </li>
                                                </ul>
                                                <div class="user-group">
                                                    @foreach ($deal->users as $user)
                                                        <img src="@if ($user->avatar) {{ asset('/storage/uploads/avatar/' . $user->avatar) }} @else {{ asset('storage/uploads/avatar/avatar.png') }} @endif"
                                                            data-bs-toggle="tooltip" title="{{ $user->name }}"
                                                            style="margin: 0; width: 15px; height: 15px;">
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>

            @if ($total_records > 0)
                @include('layouts.pagination', [
                    'total_pages' => $total_records,
                    'num_results_on_page' => 25,
                ])
            @endif
        </div>
    </div>
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script>
    /* Set the width of the side navigation to 250px and the left margin of the page content to 250px and add a black background color to body */
    function openNav(deal_id) {
        var ww = $(window).width()


        if (ww < 500) {
            $("#mySidenav").css('width', ww + 'px');
            $("#main").css('margin-right', ww + 'px');
        } else {
            $("#mySidenav").css('width', '500px');;
            $("#main").css('margin-right', "500px");
        }

        $("#modal-discussion-add").attr('data-deal-id', deal_id);
        $('.modal-discussion-add-span').removeClass('ti-minus');
        $('.modal-discussion-add-span').addClass('ti-plus');
        $(".add-discussion-div").addClass('d-none');
        $(".block-screen").css('display', 'block');
        $("#body").css('overflow', 'hidden');

        var csrf_token = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: "/deals/getDiscussions",
            data: {
                deal_id,
                _token: csrf_token,
            },
            type: "POST",
            cache: false,
            success: function(data) {
                data = JSON.parse(data);
                //console.log(data);

                if (data.status) {
                    $(".discussion-list-group").html(data.content);
                    $(".deal_id").val(deal_id);
                }
            }
        });

    }

    /* Set the width of the side navigation to 0 and the left margin of the page content to 0, and the background color of body to white */
    function closeNav() {
        $("#mySidenav").css("width", '0');
        $("#main").css("margin-right", '0');
        $("#modal-discussion-add").removeAttr('data-deal-id');
        $('.modal-discussion-add-span').removeClass('ti-minus');
        $('.modal-discussion-add-span').addClass('ti-plus');
        $(".add-discussion-div").addClass('d-none');
        $(".block-screen").css('display', 'none');
        $("#body").css('overflow', 'visible');
    }

    $(document).on("change", "#default_pipeline_id", function() {
        $('#change-pipeline').submit();
    });
</script>
