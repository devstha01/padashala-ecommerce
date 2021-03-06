@extends('backend.layouts.master')

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
                                    <h4> {{__('dashboard.Edit Banner')}}</h4>
                                </div>
                                <div class="portlet-body form">

                                    <form action="{{route('admin-update-banner',$homebanner->id)}}" method="post"
                                          enctype="multipart/form-data">
                                        <div class="form-body">
                                            {{csrf_field()}}

                                            <input type="hidden" name="x1" value=""/>
                                            <input type="hidden" name="y1" value=""/>
                                            <input type="hidden" name="h1" value=""/>
                                            <input type="hidden" name="w1" value=""/>
                                            <div class="row">
                                                <div class="col-md-4">

                                                    <label>{{__('dashboard.Banner Type')}}</label>
                                                    <select name="type" class="form-control" id="banner_type">
                                                        <option value="link" {{($homebanner->type =='link')?'selected':''}}>
                                                            {{__('dashboard.Link')}}
                                                        </option>
                                                        <option value="product"{{($homebanner->type =='product')?'selected':''}}>
                                                            {{__('dashboard.Product')}}
                                                        </option>
                                                    </select>
                                                    <br>
                                                    <div class="form-group">
                                                        <label for="exampleInputFile1">{{__('dashboard.Banner Image')}}</label>
                                                        <input type="file" id="exampleInputFile1" name="image"
                                                               class="image">
                                                        <span class="text-info">{{__('dashboard.Upload image with min-dimension : 825 x 512 pixels')}}</span>
                                                        <span style="color: red">{{$errors->first('image')??''}}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-8" style="min-height:150px">
                                                    <div class="form-group">
                                                        <label class="control-label">{{__('dashboard.Website Url')}}</label>
                                                        <input type="text" name="url"
                                                               class="form-control" value="{{$homebanner->url}}">
                                                        <span style="color: red">{{$errors->first('url')??''}}</span>
                                                    </div>
                                                    <div class="form-group" style="display:{{($homebanner->type !=='product')?'none':'block'}}"  id="product_link">
                                                        <label class="control-label">{{__('dashboard.Product Link (slug)')}}</label>
                                                        <input type="text" name="slug"
                                                               class="form-control" value="{{$homebanner->slug}}">
                                                        <span style="color: red">{{$errors->first('url')??''}}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <button type="submit" class="btn blue btn-lg" style="float:right">
                                                {{__('dashboard.save & continue')}}
                                            </button>
                                        </div>
                                        <br>
                                    </form>
                                    <br>
                                    <div class="row mt-5">
                                        <p><img id="previewimage"
                                                src="{{asset('image/homebanner/'.$homebanner->image)}}"/></p>
                                        @if(session('path'))
                                            <img src="{{ session('path') }}"/>
                                        @endif
                                    </div>
                                    <br>
                                    <a href="{{route('admin-banner')}}" class="btn btn-primary btn-lg">{{__('dashboard.Cancel')}}</a>
                                    <br>
                                    <br>
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
    <script src="{{ asset('backend/plugin/scripts/jquery.imgareaselect.min.js') }}"></script>

    <script>
        $(function () {
            $('.date-picker').datepicker({
                rtl: App.isRTL(),
                orientation: "left",
                autoclose: true,
                format: 'yyyy-mm-dd',
                startDate: '-3d'
            });


            $('#banner_type').on('change', function () {
                var value = $(this).val();
                if (value === 'product') {
                    $('#product_link').fadeIn(300);
                } else {
                    $('#product_link').fadeOut(300);
                }
            });

            var p = $("#previewimage");
            $("body").on("change", ".image", function () {

                var imageReader = new FileReader();
                imageReader.readAsDataURL(document.querySelector(".image").files[0]);

                imageReader.onload = function (oFREvent) {
                    p.attr('src', oFREvent.target.result).fadeIn();
                };
            });


            $('#previewimage').imgAreaSelect({
                // fadeSpeed : 1,
                show: true,
                handles: true,
                minHeight: 256,
                minWidth: 412.5,
                aspectRatio: '825:512',
                onSelectEnd: function (img, selection) {
                    // console.log(selection);
                    $('input[name="x1"]').val(maintainRationOnResize(selection.x1, img.naturalWidth));
                    $('input[name="y1"]').val(maintainRationOnResize(selection.y1, img.naturalWidth));
                    $('input[name="h1"]').val(maintainRationOnResize(selection.height, img.naturalWidth));
                    $('input[name="w1"]').val(maintainRationOnResize(selection.width, img.naturalWidth));
                }
            });

            function maintainRationOnResize(correction, standard) {
                return parseInt((correction * standard) / 800);
            }
        });
    </script>
@stop

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('backend/plugin/css/imgareaselect-default.css') }}">
@endsection