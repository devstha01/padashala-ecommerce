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
                                <li class="active" style="border-top: 1px solid red">
                                    <a href="{{route('edit-product-merchant',$product->slug)}}"> {{__('dashboard.General')}} </a>
                                </li>
                                <li class="">
                                    <a href="{{route('variant-edit-product-merchant',$product->slug)}}"> {{__('dashboard.Product Options')}} </a>
                                </li>
                                <li class="">
                                    <a href="{{route('specs-edit-product-merchant',$product->slug)}}"> Specifications </a>
                                </li>
                                <li class="">
                                    <a href="{{route('image-edit-product-merchant',$product->slug)}}"> {{__('dashboard.Images')}} </a>
                                </li>
                            </ul>
                            <div>
                                <form action="{{route('edit-product-merchant-post',$product->id)}}"
                                      method="post"
                                      enctype="multipart/form-data">
                                    {{csrf_field()}}

                                    <div class="row">
                                        <div class="col-sm-12">

                                            <div class="form-group">
                                                <label>
                                                    {{__('dashboard.Product Name')}} <span
                                                            class="m-l-5 text-danger">*</span>
                                                </label>
                                                <input type="text" name="name" class="form-control input-sm"
                                                       value="{{$product->eng_name??''}}" required>

                                                <span style="color: red">{{$errors->first('name')??''}}</span>
                                            </div>
                                        </div>

                                        {{--<div class="col-md-6">--}}

                                        {{--<div class="form-group">--}}
                                        {{--<label class="control-label">{{__('dashboard.Your Business Detail')}}</label>--}}
                                        {{--<input type="hidden" name="merchant_business_id"--}}
                                        {{--value="{{$merchant->getBusiness->id}}">--}}
                                        {{--<input type="text" value="{{$merchant->getBusiness->name}}"--}}
                                        {{--class="form-control input-sm" disabled>--}}
                                        {{--<span style="color: red">{{$errors->first('merchant_business_id')??''}}</span>--}}
                                        {{--</div>--}}
                                        {{--</div>--}}
                                    </div>

                                    <div class="form-group">
                                        <label>Highlights</label>
                                        <button class="btn green" id="add-highlight">+ more highlights</button>
                                        <br>
                                        <table class="table table-borderless">
                                            <tbody id="detail-highlights">
                                            @forelse($detailName??[] as $key=>$value)
                                                <tr class="highlight">
                                                    <td style="width:30%"><input type="text" name="detailName[]"
                                                                                 class="form-control"
                                                                                 value="{{$detailName[$key]??''}}">
                                                    </td>
                                                    <td style="width:65%"><input type="text" name="detailValue[]"
                                                                                 class="form-control"
                                                                                 value="{{$detailValue[$key]??''}}">
                                                    </td>
                                                    <td style="width:5%">
                                                        <button class="btn red remove-highlight">x</button>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr class="highlight">
                                                    <td style="width:30%"><input type="text" name="detailName[]"
                                                                                 class="form-control"></td>
                                                    <td style="width:65%"><input type="text" name="detailValue[]"
                                                                                 class="form-control"></td>
                                                    <td style="width:5%">
                                                        <button class="btn red remove-highlight">x</button>
                                                    </td>
                                                </tr>
                                            @endforelse
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="form-group">
                                        <label>
                                            {{__('dashboard.Detail description')}}
                                        </label>
                                        <textarea class="form-control" name="description"
                                                  id="ckeditor-replace"
                                                  style="resize: none">{{$product->eng_description??''}}</textarea>

                                    </div>


                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>
                                                    {{__('dashboard.Product Commission')}} <span
                                                            class="m-l-5 text-danger">*</span>
                                                </label>
                                                <input type="text" name="product_share" id="product_share"
                                                       class="form-control input-sm"
                                                       value="{{$product->share_percentage??0}}" required>
                                                <span style="color: red">{{$errors->first('product_share')??''}}</span>
                                            </div>

                                        </div>
                                        <div class="col-md-5">

                                            <div class="form-group">
                                                <label>
                                                    {{__('dashboard.Category')}} <span
                                                            class="m-l-5 text-danger">*</span>
                                                </label>
                                                <br>
                                                <span style="color: red">{{$errors->first('category_id')??''}}</span>
                                                <select id="product_category" name="category_id"
                                                        class="form-control input-sm" required>
                                                    <option value="">{{__('dashboard. -- select category --')}}</option>
                                                    @forelse($categories as $category)
                                                        <option value="{{$category->id}}"
                                                                {{($product->category_id ===$category->id)?'selected':''}}
                                                                data-share="{{$category->share_percentage??0}}">{{$category->name}}</option>
                                                    @empty
                                                        <option value="">{{__('dashboard.No category available')}}</option>
                                                    @endforelse
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group category_share ">
                                                <label>
                                                    {{__('dashboard.Category Commission')}}
                                                </label>
                                                <br>
                                                <input type="text" class="form-control input-sm text-right"
                                                       readonly="readonly" value="0" id="category_share">
                                            </div>
                                        </div>
                                    </div>

                                    {{--<div class="form-group">--}}
                                    {{--<label>--}}
                                    {{--{{__('dashboard.Category')}} <span--}}
                                    {{--class="m-l-5 text-danger">*</span>--}}
                                    {{--</label>--}}
                                    {{--<br>--}}
                                    {{--<span style="color: red">{{$errors->first('category_id')??''}}</span>--}}
                                    {{--<select id="product_category" name="category_id"--}}
                                    {{--class="form-control input-sm" required>--}}
                                    {{--<option value="">{{__('dashboard. -- select category --')}}</option>--}}
                                    {{--@forelse($categories as $category)--}}
                                    {{--<option value="{{$category->id}}" {{($product->category_id ===$category->id)?'selected':''}}>{{$category->name}}</option>--}}
                                    {{--@empty--}}
                                    {{--<option value="">{{__('dashboard.No category available')}}</option>--}}
                                    {{--@endforelse--}}
                                    {{--</select>--}}
                                    {{--</div>--}}

                                    <div class="form-group">
                                        <label>
                                            {{__('dashboard.Sub-Category')}}
                                        </label>
                                        <span style="color: red">{{$errors->first('sub_category_id')??''}}</span>
                                        <select id="product_sub_category" name="sub_category_id"
                                                class="form-control input-sm">
                                            <option value="">{{__('dashboard. -- select a sub-category --')}}</option>
                                            @forelse($product->getCategory->getSubCategory as $subcat)
                                                <option value="{{$subcat->id}}" {{($product->sub_category_id ===$subcat->id)?'selected':''}}>{{$subcat->name}}</option>
                                            @empty
                                                <option value="">{{__('dashboard.No sub-category available')}}</option>
                                            @endforelse
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>
                                            {{__('dashboard.Sub-Child-Category')}}
                                        </label>
                                        <span style="color: red">{{$errors->first('sub_child_category_id')??''}}</span>
                                        <select id="product_sub_child_category" name="sub_child_category_id"
                                                class="form-control input-sm">
                                            @if(!empty($product->sub_category_id))
                                                <option value="">{{__('dashboard. -- select a sub-child-category --')}}</option>
                                                @forelse($product->getSubCategory->getSubChildCategory as $subchildcat)
                                                    <option value="{{$subchildcat->id}}" {{($product->sub_child_category_id ===$subchildcat->id)?'selected':''}}>{{$subchildcat->name}}</option>
                                                @empty
                                                    <option value="">{{__('dashboard.No sub-child-category available')}}</option>
                                                @endforelse
                                            @else
                                                <option value="">{{__('dashboard.No sub-child-category available')}}</option>
                                            @endif
                                        </select>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group"><label>VAT</label><input type="number" name="vat"
                                                                                             class="form-control" value="{{$product->vat}}"> <span
                                                        style="color: red">{{$errors->first('vat')??''}}</span></div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group"><label>TAX</label><input type="number" name="tax"
                                                                                             class="form-control" value="{{$product->tax}}"> <span
                                                        style="color: red">{{$errors->first('tax')??''}}</span></div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group"><label>Excise</label><input type="number" name="excise"
                                                                                                class="form-control" value="{{$product->excise}}"> <span
                                                        style="color: red">{{$errors->first('excise')??''}}</span></div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="exampleInputFile1">{{__('dashboard.Featured image')}}</label>
                                        <input type="file" class="image" id="exampleInputFile1"
                                               name="featured_image">
                                        @if(!empty($product->featured_image))
                                            <br>{{__('dashboard.previous image')}} : <br>
                                            <img src="{{asset('/image/products/'.$product->featured_image)}}"
                                                 alt="image" height="100px">
                                        @endif
                                    </div>
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
                                    {{--<div class="form-group" style="font-size: 16px  ">--}}
                                    {{--<label>{{__('dashboard.Purchase Option')}}</label>--}}
                                    {{--<br>--}}
                                    {{--<input type="radio" name="delivery_option" value="true"--}}
                                    {{--id="true_delivery" {{$product->delivery_option?'checked':''}}>--}}
                                    {{--<label style="padding-right:20px"--}}
                                    {{--for="true_delivery"><b>{{__('dashboard.Deliver')}}</b></label>--}}
                                    {{--<input type="radio" name="delivery_option" value="false"--}}
                                    {{--id="false_delivery" {{$product->delivery_option?'':'checked'}}>--}}
                                    {{--<label style="padding-right:20px"--}}
                                    {{--for="false_delivery"><b>{{__('dashboard.View only')}}</b></label>--}}
                                    {{--</div>--}}

                                    <button type="submit" class="btn btn-primary btn-lg"> save</button>
                                    <br>
                                    <br>
                                    <br>
                                </form>
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