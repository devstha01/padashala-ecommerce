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
                                <h1>{{__('dashboard.Add New Product')}}
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container">
                    <div class="portlet light">
                        <div class="portlet-body">

                            @include('fragments.message')
                            <form action="{{route('admin-add-product-post',$merchant->id)}}" method="post"
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
                                                   value="{{old('name')??''}}" required>
                                            <span style="color: red">{{$errors->first('name')??''}}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Highlights</label>
                                    <button class="btn green" id="add-highlight">+ more highlights</button>
                                    <br>
                                    <table class="table table-borderless">
                                        <tbody id="detail-highlights">
                                        @forelse(old('detailName')??[] as $key=>$value)
                                            <tr class="highlight">
                                                <td style="width:30%"><input type="text" name="detailName[]"
                                                                             class="form-control"
                                                                             value="{{old('detailName')[$key]??''}}">
                                                </td>
                                                <td style="width:65%"><input type="text" name="detailValue[]"
                                                                             class="form-control"
                                                                             value="{{old('detailValue')[$key]??''}}">
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
                                              style="resize: none">{{old('description')??''}}</textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">

                                        <div class="form-group">
                                            <label>
                                                {{__('dashboard.Product Commission')}} <span
                                                        class="m-l-5 text-danger">*</span>
                                            </label>
                                            <input type="text" name="product_share" id="product_share"
                                                   class="form-control input-sm"
                                                   value="{{old('product_share')??0}}" required>
                                            <span style="color: red">{{$errors->first('product_share')??''}}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-5">

                                        <div class="form-group">
                                            <label>
                                                {{__('dashboard.Category')}} <span class="m-l-5 text-danger">*</span>
                                            </label>
                                            <br>
                                            <span style="color: red">{{$errors->first('category_id')??''}}</span>
                                            <select id="product_category" name="category_id"
                                                    class="form-control input-sm" required>
                                                <option value="">{{__('dashboard. -- select category --')}}</option>
                                                @forelse($categories as $category)
                                                    <option value="{{$category->id}}"
                                                            data-share="{{$category->share_percentage??0}}">{{$category->name}}
                                                    </option>
                                                @empty
                                                    <option value="">{{__('dashboard.No category available')}}</option>
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group category_share">
                                            <label>
                                                {{__('dashboard.Category Commission')}}
                                            </label>
                                            <br>
                                            <input type="text" class="form-control input-sm text-right"
                                                   readonly="readonly" value="0" id="category_share">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>
                                        {{__('dashboard.Sub-Category')}}
                                        {{--<span class="m-l-5 text-danger">*</span>--}}
                                    </label>
                                    <br> <span style="color: red">{{$errors->first('sub_category_id')??''}}</span>
                                    <select id="product_sub_category" name="sub_category_id"
                                            class="form-control input-sm">
                                        <option value="">{{__('dashboard.No sub-category available')}}</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>
                                        {{__('dashboard.Sub-Child-Category')}}
                                        {{--<span class="m-l-5 text-danger">*</span>--}}
                                    </label>
                                    <br> <span style="color: red">{{$errors->first('sub_child_category_id')??''}}</span>
                                    <select id="product_sub_child_category" name="sub_child_category_id"
                                            class="form-control input-sm">
                                        <option value="">{{__('dashboard.No sub-child-category available')}}</option>
                                    </select>
                                </div>


                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group"><label>VAT</label><input type="number" name="vat"
                                                                                         class="form-control" value="{{old('vat')}}"> <span
                                                    style="color: red">{{$errors->first('vat')??''}}</span></div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group"><label>TAX</label><input type="number" name="tax"
                                                                                         class="form-control" value="{{old('tax')}}"> <span
                                                    style="color: red">{{$errors->first('tax')??''}}</span></div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group"><label>Excise</label><input type="number" name="excise"
                                                                                            class="form-control" value="{{old('excise')}}"> <span
                                                    style="color: red">{{$errors->first('excise')??''}}</span></div>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label for="exampleInputFile1">{{__('dashboard.Featured image')}} <span
                                                class="m-l-5 text-danger">*</span></label>
                                    <input type="file" class="image" id="exampleInputFile1" name="featured_image"
                                           required>
                                    <span style="color: red">{{$errors->first('featured_image')??''}}</span>

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
                                {{--<input type="radio" name="delivery_option" value="true" id="true_delivery" checked>--}}
                                {{--<label style="padding-right:20px"--}}
                                {{--for="true_delivery"><b>{{__('dashboard.Deliver')}}</b></label>--}}
                                {{--<input type="radio" name="delivery_option" value="false"--}}
                                {{--id="false_delivery">--}}
                                {{--<label style="padding-right:20px"--}}
                                {{--for="false_delivery"><b>{{__('dashboard.View only')}}</b></label>--}}
                                {{--</div>--}}

                                <hr>
                                {{__('dashboard.Options Manage')}}

                                <table class="table table-borderless"
                                       style="border: 1px solid lightgrey;padding:5px;border-bottom:none">
                                    <thead>
                                    <tr>
                                        <th style="width: 15%">{{__('dashboard.Color Family')}}</th>
                                        <th>{{__('dashboard.Size')}}</th>
                                        <th>{{__('dashboard.Marked price')}}</th>
                                        <th>{{__('dashboard.Sell price')}}</th>
                                        <th>{{__('dashboard.Discount')}}</th>
                                        <th class="check-delivery-option" colspan="2">{{__('dashboard.Stock')}}
                                            /{{__('dashboard.Quantity')}}</th>
                                        <th>{{__('dashboard.Action')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody class="options-table-body">
                                    <tr class="option-0">
                                        <td>
                                            <select name="color[]" class="form-control color-options-0"
                                                    required></select>
                                        </td>
                                        <td><input type="text" name="size[]" class="form-control"></td>
                                        <td><input type="text" min="0" name="marked_price[]"
                                                   class="form-control marked-price"></td>
                                        <td><input type="text" min="0" name="sell_price[]"
                                                   class="form-control sell-price" required></td>
                                        <td><input type="text" min="0" max="99" name="discount_price[]"
                                                   class="form-control discount"></td>
                                        <td class="check-delivery-option"><label>ManageStock
                                                <input type="checkbox" class="stock-option-input-0" checked>
                                            </label>
                                            <input type="hidden" class="stock-option-value" name="stock_option[]"
                                                   value="true">
                                        </td>
                                        <td class="check-delivery-option">
                                            <span class="quantity">Total quantity</span>
                                            <input type="number" min="0" name="quantity[]" class="form-control">
                                        </td>
                                        <td>
                                            <a class="btn red remove-option" data-option="0"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td colspan="7">
                                            <button class="form-control btn-primary-outline options-add-btn"><i
                                                        class="fa fa-plus"></i> {{__('dashboard.Add new option')}}
                                            </button>
                                        </td>
                                    </tr>
                                    </tfoot>
                                </table>
                                <button type="submit"
                                        class="btn btn-primary btn-lg"> {{__('dashboard.save & continue')}}</button>
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
@endsection

@section('scripts')
    <script src="{{URL::asset('backend/js/merchant/create-product-merchant.js')}}"
            type="text/javascript"></script>

    <script src="{{ asset('backend/plugin/scripts/jquery.imgareaselect.min.js') }}"></script>
    <script src="{{ asset('backend/js/merchant/imageselect-config.js') }}"></script>
@stop

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('backend/plugin/css/imgareaselect-default.css') }}">
@endsection