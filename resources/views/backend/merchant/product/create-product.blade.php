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
                            <form action="{{route('create-product-first')}}" method="post"
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
                                    {{--<div class="col-md-4">--}}

                                    {{--<div class="form-group">--}}
                                    {{--<label class="control-label">{{__('dashboard.Your Business Name')}}</label>--}}
                                    {{--<input type="hidden" name="merchant_business_id"--}}
                                    {{--value="{{$merchant->getBusiness->id}}">--}}
                                    {{--<input type="text" value="{{$merchant->getBusiness->name}}"--}}
                                    {{--class="form-control input-sm" disabled>--}}
                                    {{--<span style="color: red">{{$errors->first('merchant_business_id')??''}}</span>--}}
                                    {{--</div>--}}

                                    {{--</div>--}}
                                </div>

                                <div class="form-group">
                                    <label>
                                        {{__('dashboard.Brief description')}}
                                    </label>
                                    <textarea class="form-control" name="detail"
                                              style="resize: none">{{old('detail')??''}}</textarea>
                                </div>

                                <div class="form-group">
                                    <label>
                                        {{__('dashboard.Detail description')}}
                                    </label>
                                    <textarea class="form-control" name="description"
                                              style="resize: none">{{old('description')??''}}</textarea>
                                </div>
                                <div class="row">
                                    <div class="col-sm-8">

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
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>
                                                {{__('dashboard.Category Share')}}
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
                                    <div class="col-sm-8">
                                        <div class="form-group">
                                            <label>
                                                {{__('dashboard.Product Share')}} <span
                                                        class="m-l-5 text-danger">*</span>
                                            </label>
                                            <input type="text" name="product_share" id="product_share"
                                                   class="form-control input-sm"
                                                   value="{{old('product_share')??''}}" required>
                                            <span style="color: red">{{$errors->first('product_share')??''}}</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>
                                                {{__('dashboard.Net Share Percentage')}}
                                            </label>
                                            <br>
                                            <input type="text" class="form-control input-sm text-right"
                                                   readonly="readonly" value="0" id="net_share">
                                        </div>

                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputFile1">{{__('dashboard.Featured image')}} <span
                                                class="m-l-5 text-danger">*</span></label>
                                    <input type="file" id="exampleInputFile1" name="featured_image" required>
                                    <span style="color: red">{{$errors->first('featured_image')??''}}</span>
                                </div>


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
                                        <th>{{__('dashboard.Quantity')}}</th>
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
                                                   class="form-control marked-price" required></td>
                                        <td><input type="text" min="0" name="sell_price[]"
                                                   class="form-control sell-price" required></td>
                                        <td><input type="text" min="0" max="99" name="discount_price[]"
                                                   class="form-control discount" required></td>
                                        <td><input type="number" min="0" name="quantity[]" class="form-control"
                                                   required></td>
                                        <td>
                                            <a class="btn red remove-option" data-option="0"><i
                                                        class="fa fa-trash"></i></a>
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
@stop