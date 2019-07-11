@extends('backend.layouts.master')
@section('stylesheets')
    <script src="https://cloud.tinymce.com/5/tinymce.min.js"></script>
    <script>tinymce.init({selector: 'textarea'});</script>
@endsection
@section('content')
    <div class="page-wrapper-row full-height">
        <div class="page-wrapper-middle">
            <div class="page-container">
                <div class="container">
                    <div class="row">
                        @include('fragments.message')
                        <div class="col-md-12">
                            <br>
                            <div class="portlet light">
                                <div class="portlet-title">
                                    <h4> {{__('dashboard.About Us')}}</h4>
                                </div>
                                <div class="portlet-body form">
                                    <form action="{{$url}}" method="post"
                                          enctype="multipart/form-data">

                                        <div class="form-body">
                                            {{csrf_field()}}
                                            <div class="row">
                                                <div class="col-sm-6">


                                                    <div class="form-group">
                                                        <label class="control-label">{{__('dashboard.Title')}}</label>
                                                        <input type="text" name="title"
                                                               class="form-control" value="{{$about->title??''}}">
                                                        <span style="color: red">{{$errors->first('title')??''}}</span>
                                                    </div>


                                                    <div class="form-group">
                                                        <label for="exampleInputFile1">{{__('dashboard.Image')}}</label>
                                                        <input type="file" id="exampleInputFile1" name="image">
                                                        <span style="color: red">{{$errors->first('image')??''}}</span>
                                                    </div>

                                                </div>

                                                <div class="col-md-6">
                                                    <img src="{{asset('image/about/'.((isset($about->image))?$about->image:'not-available.jpg'))}}" height="200px" alt="_">
                                                </div>
                                                <div class="col-md-6">


                                                    <div class="form-group">
                                                        <label class="control-label">{{__('dashboard.Description')}}</label>

                                                        <textarea type="text" name="description"
                                                                  class="form-control">{!!$about->description??''!!}</textarea>
                                                        {{-- <textarea type="text" name="description"
                                                                       class="form-control" value="{{$about->description??''}}"></textarea> --}}
                                                        <span style="color: red">{{$errors->first('description')??''}}</span>
                                                    </div>

                                                    <button type="submit"
                                                            class="btn btn-primary btn-lg"> {{__('dashboard.save & continue')}}</button>
                                                </div>
                                            </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script src="{{asset('backend/assets/pages/scripts/components-date-time-pickers.min.js')}}"
            type="text/javascript"></script>
    <script>
        $('.date-picker').datepicker({
            rtl: App.isRTL(),
            orientation: "left",
            autoclose: true,
            format: 'yyyy-mm-dd',
            startDate: '-3d'
        });
    </script>
@stop