@extends('layouts.admin.app')

@section('title', translate('session List'))

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
                        <h1 class=""><i class="tio-filter-list"></i> {{translate('sessions')}} {{translate('list')}}</h1>
                    </div>
                    <div class="col-12 col-sm-6 text-sm-right text-left">
                        <a href="{{route('admin.session.add')}}" class="btn btn-primary pull-right"><i
                                class="tio-add-circle"></i> {{translate('add session')}}</a>
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
                            <h5 class="card-header-title">{{translate('session Table')}}</h5>
                            <h5 class="card-header-title text-primary mx-1">({{ $sessions->total() }})</h5>
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
                    <!-- End Header -->

                    <!-- Table -->
                    <div class="table-responsive datatable-custom">
                        <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                            <thead class="thead-light">
                            <tr>
                                <th>{{translate('id')}}</th>
                                <th>{{translate('name')}}</th>
                                <th>{{translate('course name')}}</th>
                                <th>{{translate('video_link')}}</th>
                                <th>{{translate('description')}}</th>
                                <th>{{translate('action')}}</th>
                            </tr>
                            </thead>

                            <tbody id="set-rows">
                                @foreach($sessions as $key=>$session)
                                    <tr>
                                        <td>{{$session['id']}}</td>
                                        <td>
                                            <span class="d-block font-size-sm text-body">
                                                {{$session['title']}}
                                            </span>
                                        </td>
                                        <td>{{$session->course->author}}</td>
                                        <td>{{$session['video_link']}}</td>
                                        <td>{{$session['description']}}</td>
                                        <td>
                                            <!-- Dropdown -->
                                            <div class="dropdown">
                                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="tio-settings"></i>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item" href="{{route('admin.session.edit',[$session['id']])}}">
                                                        <i class="tio-edit"></i>{{translate('edit')}}
                                                    </a>
                                                    <a class="dropdown-item" href="{{route('admin.session.preview',[$session['id']])}}">
                                                        <i class="tio-file"></i>{{translate('view')}}
                                                    </a>
                                                    <a class="dropdown-item" href="javascript:"
                                                       onclick="form_alert('session-{{$session['id']}}','{{translate('Want to remove this information ?')}}')">
                                                        <i class="tio-remove-from-trash"></i>{{translate('delete')}}
                                                    </a>
                                                    <form action="{{route('admin.session.delete',[$session['id']])}}" method="post"
                                                        id="session-{{$session['id']}}">
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
                                {!! $sessions->links() !!}

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

        $('#session').on('change', function () {
            var session_id = $(this).val();
            var url = '{{ url()->current() }}' + '?session=' + session_id;
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
