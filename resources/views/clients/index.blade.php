@extends('layouts.admin')
@php
   // $profile=asset(Storage::url('uploads/avatar/'));
    $profile=\App\Models\Utility::get_file('uploads/avatar/');
@endphp
@section('page-title')
    {{__('Manage Contacts')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Contacts')}}</li>
@endsection
@section('content')
<style>
    table tr td{
        font-size: 14px;
    }
</style>
    <div class="row">
        <div class="card py-3">
            <div class="row align-items-center ps-0 ms-0 pe-4 my-2">
                <div class="col-2">
                    <p class="mb-0 pb-0">CONTACTS</p>
                    <div class="dropdown">
                        <button class="dropdown-toggle All-leads" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            ALL CONTACTS
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <!-- <li><a class="dropdown-item" href="#">Action</a></li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li> -->
                            <li><a class="dropdown-item delete-bulk-contacts" href="javascript:void(0)">Delete</a></li>
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
                    <button class="btn filter-btn-show p-2 btn-dark" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="ti ti-filter" style="font-size:18px"></i>
                    </button>
                    <button data-url="{{ route('clients.create') }}" data-ajax-popup="true"  data-bs-toggle="tooltip" title="{{__('Create')}}" class="btn btn-sm p-2 btn-dark" data-bs-toggle="modal">
                        <i class="ti ti-plus" style="font-size:18px"></i>
                    </button>

                </div>
            </div>

            {{-- Filters --}}
            <div class="filter-data px-3" id="filter-show" <?= isset($_GET) && !empty($_GET) ? '' : 'style="display: none;"' ?>>
                <form action="/clients" method="GET" class="">
                    <div class="row my-3">
                        <div class="col-md-4 mt-2">
                            <label for="">Name</label>
                            <input type="text" class="form form-control" placeholder="Search Name" name="name" value="<?= isset($_GET['name']) ? $_GET['name'] : '' ?>" style="width: 95%; border-color:#aaa">
                        </div>

                        <div class="col-md-4 mt-2">
                            <label for="">Email</label>
                            <input type="text" class="form form-control" placeholder="Search Email" name="email" value="<?= isset($_GET['email']) ? $_GET['email'] : '' ?>" style="width: 95%; border-color:#aaa">
                        </div>

                        {{-- <div class="col-md-4 mt-2">
                            <label for="">Admissions</label>
                            <input type="text" class="form form-control" placeholder="Search Admissions" name="admissions" value="<?= isset($_GET['admissions']) ? $_GET['admissions'] : '' ?>" style="width: 95%; border-color:#aaa">
                        </div>

                        <div class="col-md-4 mt-2">
                            <label for="">Applications</label>
                            <input type="text" class="form form-control" placeholder="Search Applications" name="applications" value="<?= isset($_GET['applications']) ? $_GET['applications'] : '' ?>" style="width: 95%; border-color:#aaa">
                        </div> --}}


                        <div class="col-md-4 mt-3">
                            <br>
                            <input type="submit" class="btn me-2 bg-dark" style=" color:white;">
                            <a href="/clients" class="btn bg-dark" style="color:white;">Reset</a>
                        </div>
                    </div>
                    <div class="row">
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

            <div class=" mt-3">
                <table class="table">
                    <thead style="background: #ddd; color:rgb(0, 0, 0); font-size: 14px; font-weight: bold;">
                        <tr>
                            <!-- <td style="border-left: 1px solid #fff;"></td> -->
                            <th style="width: 50px !important;">
                                <input type="checkbox" class="main-check">
                            </th>
                            <th style="border-left: 1px solid #fff;">Name</th>
                            <th style="border-left: 1px solid #fff;">Email</th>
                            <th style="border-left: 1px solid #fff;">Admissions</th>
                            <th style="border-left: 1px solid #fff;">Applications</th>
                            <th style="border-left: 1px solid #fff;">Action</th>
                        </tr>
                    </thead>
                    <tbody class="leads-list-div" style="color:rgb(0, 0, 0); font-size: 14px;" class="new-organization-list-tbody">

                        @forelse($clients as $client)
                            <tr>
                                <td>
                                    <input type="checkbox" name="contacts[]" value="{{$client->id}}" class="sub-check">
                                </td>
                                <td><span style="cursor:pointer" class="hyper-link" onclick="openSidebar('/clients/'+{{$client->id}}+'/client_detail')" >
                                        {{ $client->name }}
                                    </span>

                                </td>
                                <td>{{$client->email}}</td>
                                <td>{{$client->clientDeals->count()}}</td>
                                <td>{{$client->clientApplications($client->id)}}</td>
                                <td>

                                    <div class="card-header-right">
                                        <div class="btn-group card-option">
                                            <button type="button" class="btn"
                                                    data-bs-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                <i class="ti ti-dots-vertical"></i>
                                            </button>

                                            <div class="dropdown-menu dropdown-menu-end">
                                                @can('edit client')
                                                    <a href="#!" data-size="md" data-url="{{ route('clients.edit',$client->id) }}" data-ajax-popup="true" class="dropdown-item" data-bs-original-title="{{__('Edit User')}}">
                                                        <i class="ti ti-pencil"></i>
                                                        <span>{{__('Edit')}}</span>
                                                    </a>
                                                @endcan

                                                @can('delete client')
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['clients.destroy', $client['id']],'id'=>'delete-form-'.$client['id']]) !!}
                                                    <a href="#!"  class="dropdown-item bs-pass-para">
                                                        <i class="ti ti-archive"></i>
                                                        <span> @if($client->delete_status!=0){{__('Delete')}} @else {{__('Restore')}}@endif</span>
                                                    </a>

                                                    {!! Form::close() !!}
                                                @endcan

                                                <a href="#!" data-url="{{route('clients.reset',\Crypt::encrypt($client->id))}}" data-ajax-popup="true" class="dropdown-item" data-bs-original-title="{{__('Reset Password')}}">
                                                    <i class="ti ti-adjustments"></i>
                                                    <span>  {{__('Reset Password')}}</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>

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
        </div>
    </div>
@push('script-page')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<script>
    $(document).on('change', '.main-check', function() {
        $(".sub-check").prop('checked', $(this).prop('checked'));
    });
    $('.filter-btn-show').click(function() {
            $("#filter-show").toggle();
        });
    $(document).on("click", '.delete-bulk-contacts', function() {
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
                window.location.href = '/delete-bulk-contacts?ids='+selectedIds.join(',');
            }
        });
    })
</script>

@endpush

@endsection

