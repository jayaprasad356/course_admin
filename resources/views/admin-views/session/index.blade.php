@extends('layouts.admin.app')

@section('title', translate('Add new session'))
<style>
    .password-container {
        position: relative;
    }

    .togglePassword {
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
        <div class="pb-3">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class=""><i class="tio-add-circle-outlined"></i> {{translate('add')}} {{translate('new')}} {{translate('session')}}</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <form action="{{ route('admin.session.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-8 col-12">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('name')}}<i class="text-danger asterik">*</i></label>
                                <input type="text" name="name" class="form-control" placeholder="{{translate('session Name')}}" required>
                            </div>
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{ translate('course') }}<i class="text-danger asterik">*</i></label>
                                <select name="course_id" class="form-control" required>
                                    <option value="">{{ translate('Select a course') }}</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->id }}">{{ $course->author }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('video_link')}}<i class="text-danger asterik">*</i></label>
                                <input type="text" name="video_link" class="form-control" placeholder="{{translate('session video_link')}}" required>
                            </div>
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('description')}}<i class="text-danger asterik">*</i></label>
                                <input type="text" name="description" class="form-control" placeholder="{{translate('session description')}}" required>
                            </div>
                            <div class="form-group">
                                <label class="input-label">{{ translate('Videos') }}</label>
                                <input type="file" name="videos[]" class="form-control" multiple>
                            </div>
                            <button type="submit" class="btn btn-primary">{{translate('submit')}}</button>
                            <input type="reset" onClick="refreshPage()" class="btn-warning btn" value="Clear" />
                        </div>
                    </div>
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
                    $('#' + viewer_id).attr('src', e.target.result);
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
        $('#btnClear').on('click', function () {
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].setData('');
            }
        });
    </script>
    <script>
        // Update label with selected file name
        $('input[type="file"]').change(function (e) {
            var files = e.target.files;
            var label = $(this).next('.custom-file-label');
            if (files.length > 1) {
                label.html(files.length + ' ' + '{{ translate("files selected") }}');
            } else {
                label.html(files[0].name);
            }
        });
    </script>
@endpush
