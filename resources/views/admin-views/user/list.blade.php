@extends('layouts.admin.app')

@section('title', translate('user List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="pb-3">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0 row">
                    <div class="col-12 col-sm-6">
                        <h1 class=""><i class="tio-filter-list"></i> {{translate('users')}} {{translate('list')}}</h1>
                    </div>
                    <div class="col-12 col-sm-6 text-sm-right text-left">
                        <a href="{{route('admin.user.add')}}" class="btn btn-primary pull-right"><i
                                class="tio-add-circle"></i> {{translate('add user')}}</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <!-- Card -->
                <div class="card">
                    <!-- Header -->
                    <div class="card-header flex-between">
                        <div class="flex-start">
                        <form action="export-verified-user.php">
                            <button type='submit'  class="btn btn-primary"><i class="fa fa-download"></i> Export Verified Users</button>
                        </form>
                        <form action="export-unverified-user.php">
                            <button type='submit'  class="btn btn-primary"><i class="fa fa-download"></i> Export Unverified Users</button>
                        </form>
                     </div>
                        <form action="{{url()->current()}}" method="GET">
                            <div class="input-group">
                                <input id="datatableSearch_" type="search" name="search"
                                       class="form-control"
                                       placeholder="{{translate('Search')}}" aria-label="Search"
                                       value="{{$search}}" required autocomplete="off">
                                <div class="input-group-append">
                                    <button type="submit" class="input-group-text"><i class="tio-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="row">
                    <div class="col-md-3">
                        <select id="status" name="status" class="js-select2-custom"
                                        data-hs-select2-options='{
                                            "minimumResultsForSearch": "Infinity",
                                            "customClass": "custom-select custom-select-sm text-capitalize"
                                        }'>
                                    <option value="">{{translate('any')}}</option>
                                    <option value="0">{{translate('Not-Verified')}}</option>
                                    <option value="1">{{translate('Verified')}}</option>
                                    <option value="2">{{translate('Blocked')}}</option>
                                </select>
                        </div>
                        <div class="col-md-2">
                        <input type="date" id="date" name="date" class="form-control">
                        </div>
                        <div class="col-md-3">
                        <select id="trial_completed" name="trial_completed" class="js-select2-custom"
                                        data-hs-select2-options='{
                                            "minimumResultsForSearch": "Infinity",
                                            "customClass": "custom-select custom-select-sm text-capitalize"
                                        }'>
                                    <option value="">{{translate('any')}}</option>
                                    <option value="0">{{translate('Yes')}}</option>
                                    <option value="1">{{translate('no')}}</option>
                                </select>
                        </div>
                        </div>
                    <!-- End Header -->

                    <!-- Table -->
                    <div class="table-responsive datatable-custom">
                        <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                            <thead class="thead-light">
                            <tr>
                                <th>{{translate('id')}}</th>
                                <th >{{translate('name')}}</th>
                                <th >{{translate('mobile')}}</th>
                                <th>{{translate('refer_code')}}</th>
                                <th>{{translate('referred_by')}}</th>
                                <th >{{translate('total_referals')}}</th>
                                <th >{{translate('today_ads')}}</th>
                                <th >{{translate('earn')}}</th>
                                <th >{{translate('balance')}}</th>
                                <th >{{translate('current_refers')}}</th>
                                <th>{{translate('status')}}</th>
                                <th>{{translate('joined_date')}}</th>
                                <th>{{translate('action')}}</th>
                            </tr>
                            </thead>

                            <tbody id="set-rows">
                                @foreach($users as $key=>$user)
                                    <tr>
                                        <td>{{$user['id']}}</td>
                                        <td>{{$user['name']}}</td>
                                        <td>{{$user['mobile']}}</td>
                                        <td>{{$user['refer_code']}}</td>
                                        <td>{{$user['referred_by']}}</td>
                                        <td>{{$user['total_referals']}}</td>
                                        <td>{{$user['today_ads']}}</td>
                                        <td>{{$user['earn']}}</td>
                                        <td>{{$user['balance']}}</td>
                                        <td>{{$user['current_refers']}}</td>
                                        <td>
                                            @if($user['status'] == 0)
                                                <div style="margin-top:12px;">
                                                    <p class="text text-primary">{{translate('Not-Verified')}}</p>
                                                </div>
                                            @elseif($user['status'] == 1)
                                                <div style="margin-top:12px;">
                                                    <p class="text text-success">{{translate('Verified')}}</p>
                                                </div>
                                            @elseif($user['status'] == 2)
                                                <div style="margin-top:12px;">
                                                    <p class="text text-danger">{{translate('Blocked')}}</p>
                                                </div>
                                            @endif
                                        </td>
                                        <td>{{$user['joined_date']}}</td>
                                        <td>
                                            <!-- Dropdown -->
                                            <div class="dropdown">
                                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="tio-settings"></i>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item" href="{{route('admin.user.edit',[$user['id']])}}">
                                                        <i class="tio-edit"></i>{{translate('edit')}}
                                                    </a>
                                                    <a class="dropdown-item" href="{{route('admin.user.preview',[$user['id']])}}">
                                                        <i class="tio-file"></i>{{translate('view')}}
                                                    </a>
                                                    <a class="dropdown-item" href="javascript:"
                                                       onclick="form_alert('user-{{$user['id']}}','{{translate('Want to remove this information ?')}}')">
                                                        <i class="tio-remove-from-trash"></i>{{translate('delete')}}
                                                    </a>
                                                    <form action="{{route('admin.user.delete',[$user['id']])}}" method="post"
                                                        id="user-{{$user['id']}}">
                                                        @csrf @method('delete')
                                                    </form>
                                                </div>
                                            </div>
                                            <!-- End Dropdown -->
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <hr>
                        <div class="page-area">
                            <table>
                                <tfoot>
                                {!! $users->links() !!}
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <!-- End Table -->
                </div>
                <!-- End Card -->
            </div>
        </div>
    </div>
@endsection
@push('script_2')
    <script>
        $('#search-form').on('submit', function () {
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('admin.course.search') }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    $('#set-rows').html(data.view);
                    $('.page-area').hide();
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        });

        $('#user').on('change', function () {
            var user_id = $(this).val();
            var url = '{{ url()->current() }}' + '?user=' + user_id;
            window.location.href = url;
        });
    </script>
  <script>
        $(document).on('ready', function () {
            // INITIALIZATION OF DATATABLES
            // =======================================================
            // var datatable = $.HSCore.components.HSDatatables.init($('#columnSearchDatatable'));

            var datatable = $('.table').DataTable({
                "paging": false
            });

            $('#status').on('change', function () {
                datatable
                    .columns(0)
                    .search(this.value)
                    .draw();
            });
            $('#trail_completed').on('change', function () {
                datatable
                    .columns(0)
                    .search(this.value)
                    .draw();
            });
            $('#date').on('change', function() {
                datatable
                     .columns(5)
                    .search(this.value)
                    .draw();
            });

       


            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function () {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });
    </script>
@endpush
