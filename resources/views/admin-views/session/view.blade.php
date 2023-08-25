@extends('layouts.admin.app')

@section('title', translate('session Preview'))

@push('css_or_js')

@endpush

@section('content')
<div class="content container-fluid">
        <!-- Page Header -->
        <div class="pb-3">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class=""> {{translate('session')}} {{translate('details')}}</h1>
                </div>
                <div class="col-sm mb-2 mb-sm-0">
                    <a href="{{url()->previous()}}" class="btn btn-primary float-right">
                        <i class="tio-back-ui"></i> {{translate('back')}}
                    </a>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-md-6 col-12 mb-3 mb-lg-2">
                <table class="table table-bordered">
                            <tr>
                                <th style="width: 200px">ID</th>
                                <td>{{$session['id']}}</td>
                            </tr>
                            <tr>
                                <th style="width: 200px">tittle</th>
                                <td>{{$session['tittle']}}</td>
                            </tr>
                            <tr>
                                <th style="width: 200px">course</th>
                                <td>{{$session['course_id']}}</td>
                            </tr>
                            <tr>
                                <th style="width: 200px">video_link</th>
                                <td>{{$session['video_link']}}</td>
                            </tr>
                            <tr>
                                <th style="width: 200px">description</th>
                                <td>{{$session['description']}}</td>
                            </tr>
                          
                           
                </table>
            </div>
</div>
                
            </div>
        </div>
    </div>
@endsection

@push('script_2')

@endpush
