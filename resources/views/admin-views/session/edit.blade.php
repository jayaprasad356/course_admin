@extends('layouts.admin.app')

@section('title', translate('Update session'))
<style>
    .password-container{
        position: relative;
    }

    .togglePassword{
        position: absolute;
        top: 14px;
        right: 16px;
    }
</style>
@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i class="tio-edit"></i> {{translate('update session')}}</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <form action="{{route('admin.session.update',[$session['id']])}}" method="post"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-8 col-12">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('tittle')}}</label>
                                <input type="text" value="{{$session['tittle']}}" name="tittle"
                                       class="form-control" required>
                            </div>
                            <div class="form-group">
    <label for="course_id">Course</label>
    <select name="course_id" id="course_id" class="form-control">
        @foreach($courses as $key => $value)
            <option value="{{ $key }}" {{ $session->course_id == $key ? 'selected' : '' }}>{{ $value }}</option>
        @endforeach
    </select>
</div>
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('video_link')}}</label>
                                <input type="text" value="{{$session['video_link']}}" name="video_link"
                                       class="form-control" required>
                            </div>
                             <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('video_duration')}}</label>
                                <input type="time" value="{{$session['video_duration']}}" name="video_duration"
                                       class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('description')}}</label>
                                <input type="text" value="{{$session['description']}}" name="description"
                                       class="form-control" required>
                            </div>
                        </div>
                        </div>
                   </div>
</div>

                    <button type="submit" class="btn btn-primary">{{translate('submit')}}</button>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('script_2')
    <script>
        function readURL(input, viewer_id) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#'+viewer_id).attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function () {
            readURL(this, 'viewer');
        });
        $("#customFileEg2").change(function () {
            readURL(this, 'viewer2');
        });
        $("#customFileEg3").change(function () {
            readURL(this, 'viewer3');
        });
    </script>

    <script>
        // Update label with selected file name
        $('input[type="file"]').change(function(e){
            var fileName = e.target.files[0].name;
            $(this).next('.custom-file-label').html(fileName);
        });
   </script>
@endpush
