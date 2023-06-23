@extends('layouts.admin.app')

@section('title', translate('User List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="pb-3">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="">{{ translate('Users List') }}</h1>
                </div>
                <div class="col-sm-auto">
                    <a href="{{ route('admin.user.add') }}" class="btn btn-primary">
                        <i class="tio-add-circle"></i>
                        {{ translate('Add User') }}
                    </a>
                </div>
            </div>
        </div>
        <!-- End Page Header -->

        <!-- Users Table -->
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <select id="status" name="status" class="form-control">
                            <option value="">{{ translate('Any') }}</option>
                            <option value="0">{{ translate('Not-Verified') }}</option>
                            <option value="1">{{ translate('Verified') }}</option>
                            <option value="2">{{ translate('Blocked') }}</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="date" id="date" name="date" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <form action="{{ url()->current() }}" method="GET">
                            <div class="input-group">
                                <input id="datatableSearch_" type="search" name="search" class="form-control"
                                       placeholder="{{ translate('Search') }}" aria-label="Search" value="{{ $search }}"
                                       required autocomplete="off">
                                <div class="input-group-append">
                                    <button type="submit" class="input-group-text">
                                        <i class="tio-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>{{ translate('ID') }}</th>
                            <th>{{ translate('Name') }}</th>
                            <th>{{ translate('Email') }}</th>
                            <th>{{ translate('Mobile') }}</th>
                            <th>{{ translate('Password') }}</th>
                            <th>{{ translate('Joined Date') }}</th>
                            <th>{{ translate('Status') }}</th>
                            <th>{{ translate('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->mobile }}</td>
                                <td>{{ $user->password }}</td>
                                <td>{{ $user->joined_date }}</td>
                                <td>
                                    @if($user->status == 0)
                                        <span class="text text-primary">{{ translate('Not-Verified') }}</span>
                                    @elseif($user->status == 1)
                                        <span class="text text-success">{{ translate('Verified') }}</span>
                                    @elseif($user->status == 2)
                                        <span class="text text-danger">{{ translate('Blocked') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="tio-settings"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item" href="{{ route('admin.user.edit', $user->id) }}">
                                                <i class="tio-edit"></i>
                                                {{ translate('Edit') }}
                                            </a>
                                            <a class="dropdown-item" href="{{ route('admin.user.preview', $user->id) }}">
                                                <i class="tio-file"></i>
                                                {{ translate('View') }}
                                            </a>
                                            <a class="dropdown-item" href="javascript:"
                                               onclick="form_alert('user-{{$user->id}}','{{ translate('Want to remove this information?') }}')">
                                                <i class="tio-remove-from-trash"></i>
                                                {{ translate('Delete') }}
                                            </a>
                                            <form action="{{ route('admin.user.delete', $user->id) }}" method="post"
                                                  id="user-{{ $user->id }}">
                                                @csrf
                                                @method('delete')
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
        <!-- End Users Table -->
    </div>
@endsection

@push('script_2')
    <script>
        $(document).ready(function() {
            $('#status').on('change', function() {
                var status = $(this).val();
                $('table').DataTable().columns(6).search(status).draw();
            });

            $('#date').on('change', function() {
                var date = $(this).val();
                $('table').DataTable().columns(5).search(date).draw();
            });
        });
    </script>
@endpush
