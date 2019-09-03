@extends('backend.layouts.master')

@section('content')
    <div class="page-wrapper-row full-height">
        <div class="page-wrapper-middle">
            <div class="page-container">
                <div class="page-content-wrapper">
                    <div class="page-head">
                        <div class="container">
                            <!-- BEGIN PAGE TITLE -->
                            <div class="page-title">
                                <h1>{{__('dashboard.Edit Product')}}
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container">
                    <div class="portlet light">
                        <div class="portlet-body">
                            @include('fragments.message')
                            <input type="hidden" id="merchant-delivery-option" value="{{$merchant->purchase_option}}">
                            <ul class="nav nav-justified">
                                <li>
                                    <a href="{{route('edit-product-merchant',$product->slug)}}"> {{__('dashboard.General')}} </a>
                                </li>
                                <li class="">
                                    <a href="{{route('variant-edit-product-merchant',$product->slug)}}"> {{__('dashboard.Product Options')}} </a>
                                </li>
                                <li class="">
                                    <a href="{{route('specs-edit-product-merchant',$product->slug)}}"> Specifications </a>
                                </li>
                                <li class="active" style="border-top: 1px solid red">
                                    <a href="{{route('image-edit-product-merchant',$product->slug)}}"> {{__('dashboard.Images')}} </a>
                                </li>
                            </ul>
                            <div>
                            <form action="{{route('add-product-images-merchant',$product->id)}}"
                                      method="post" enctype="multipart/form-data">
                                    {{csrf_field()}}
                                    <div class="form-group">
                                        <label for="exampleInputFile2">{{__('dashboard.Additional image')}}</label>
                                        <input type="file" class="image" id="exampleInputFile2"
                                               name="image">
                                    </div>
                                    <button type="submit"
                                            class="btn btn-primary"> {{__('dashboard.add images')}}</button>
                                <br>
                                <br>
                                <br>
                                    <div>
                                        <input type="hidden" name="x1" value=""/>
                                        <input type="hidden" name="y1" value=""/>
                                        <input type="hidden" name="h1" value=""/>
                                        <input type="hidden" name="w1" value=""/>
                                        <div class="row mt-5">
                                            <p><img id="previewimage" style="display:none;"/></p>
                                            @if(session('path'))
                                                <img src="{{ session('path') }}"/>
                                            @endif
                                        </div>
                                    </div>
                                </form>
                                <hr>
                                <table class="table table-striped">
                                    <tr>
                                        <th>#</th>
                                        <th>{{__('dashboard.Image')}}</th>
                                        <th>{{__('dashboard.Action')}}</th>
                                    </tr>
                                    @forelse($product->getProductImage as $key=>$prodImg)
                                        <tr>
                                            <td>{{++$key}}</td>
                                            <td>
                                                <img src="{{asset('image/products/'.$prodImg->image)}}"
                                                     alt="image" height="100px">
                                            </td>
                                            <td>
                                                <a href="{{route('delete-product-image-merchant',$prodImg->id)}}"
                                                   class="fa fa-trash btn btn-sm btn-danger"
                                                   onclick="return confirm('are you sure ?')">
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3">{{__('dashboard.No images added')}}.</td>
                                        </tr>
                                    @endforelse
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script src="{{URL::asset('backend/assets/pages/scripts/ui-modals.min.js')}}"
            type="text/javascript"></script>
    <script src="{{URL::asset('backend/js/merchant/create-product-merchant.js')}}"
            type="text/javascript"></script>
    <script src="{{ asset('backend/plugin/scripts/jquery.imgareaselect.min.js') }}"></script>

    <script src="{{ asset('backend/js/merchant/imageselect-config.js') }}"></script>

@stop

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('backend/plugin/css/imgareaselect-default.css') }}">
@endsection