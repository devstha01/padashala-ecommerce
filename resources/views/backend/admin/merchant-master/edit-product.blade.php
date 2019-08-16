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
                    @include('fragments.message')
                    <div class="tabbable-custom nav-justified">
                        <ul class="nav nav-tabs nav-justified">
                            <li class="{{(session('active')!==null)?'':'active'}}">
                                <a href="#tab_1_1_1" data-toggle="tab"> {{__('dashboard.General')}} </a>
                            </li>
                            <li class="{{(session('active') === 'variant')?'active':''}}">
                                <a href="#tab_1_1_3"
                                   data-toggle="tab"> {{__('dashboard.Product Options')}} </a>
                            </li>
                            <li class="{{(session('active') === 'image')?'active':''}}">
                                <a href="#tab_1_1_2" data-toggle="tab">{{__('dashboard.Images')}}</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane {{(session('active')!==null)?'':'active'}}" id="tab_1_1_1">
                                <form action="{{route('admin-edit-product-post',$product->id)}}"
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
                                        <label>
                                            {{__('dashboard.Brief description')}}
                                        </label>
                                        <textarea class="form-control" name="detail"
                                                  style="resize: none">{{$product->eng_detail??''}}</textarea>
                                    </div>

                                    <div class="form-group">
                                        <label>
                                            {{__('dashboard.Detail description')}}
                                        </label>
                                        <textarea class="form-control" name="description"
                                                  id="ckeditor-replace"      style="resize: none">{{$product->eng_description??''}}</textarea>

                                    </div>
                                    <div class="row">
                                        <div class="col-sm-8">
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
                                        <div class="col-sm-8">
                                            <div class="form-group">
                                                <label>
                                                    {{__('dashboard.Product Share')}} <span
                                                            class="m-l-5 text-danger">*</span>
                                                </label>
                                                <input type="text" name="product_share" id="product_share" class="form-control input-sm"
                                                       value="{{$product->share_percentage??0}}" required>
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
                                        <label for="exampleInputFile1">{{__('dashboard.Featured image')}}</label>
                                        <input type="file" id="exampleInputFile1" name="featured_image">
                                        @if(!empty($product->featured_image))
                                            <br>{{__('dashboard.previous image')}} : <br>
                                            <img src="{{asset('/image/products/'.$product->featured_image)}}"
                                                 alt="image" height="100px">
                                        @endif
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-lg"> save</button>
                                    <br>
                                    <br>
                                    <br>
                                </form>
                            </div>
                            <div class="tab-pane {{(session('active') === 'image')?'active':''}}"
                                 id="tab_1_1_2">
                                <form action="{{route('admin-add-product-images',$product->id)}}"
                                      method="post" enctype="multipart/form-data">
                                    {{csrf_field()}}
                                    <div class="form-group">
                                        <label for="exampleInputFile1">{{__('dashboard.Additional image')}}</label>
                                        <input type="file" id="exampleInputFile1" name="image[]"
                                               multiple>
                                    </div>
                                    <button type="submit"
                                            class="btn btn-primary"> {{__('dashboard.add images')}}</button>
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
                            <div class="tab-pane {{(session('active') === 'variant')?'active':''}}"
                                 id="tab_1_1_3">
                                <table class="table table-borderless">

                                    @foreach($options as $color_id=>$option)
                                        <form action="{{route('admin-update-product-variant-image')}}" method="post"
                                              enctype="multipart/form-data">
                                            <tr>
                                                <th rowspan="{{count($option) +2}}">
                                                    @if($color_image = \App\Models\ColorImage::where('color_id',$color_id)->where('product_id',$product->id)->first())
                                                        <img src="{{asset('image/products/color/'.$color_image->image)}}"
                                                             alt=" " style="height: 120px">
                                                    @else
                                                        <span class="lead">No image</span>
                                                    @endif
                                                </th>
                                                <th colspan="3"><h4>{{__('dashboard.Color Family')}}
                                                        : {{\App\Models\Color::find($color_id)->name??' - '}}</h4></th>
                                                <td colspan="2">
                                                    {{csrf_field()}}
                                                    <input type="hidden" name="color_id" value="{{$color_id}}">
                                                    <input type="hidden" name="product_id" value="{{$product->id}}">

                                                    <input type="file" name="image" class="form-control" required>
                                                </td>
                                                <td colspan="1">
                                                    <button type="submit"
                                                            class="btn blue">{{__('dashboard.Update Color image')}}</button>
                                                </td>
                                            </tr>
                                        </form>
                                        @foreach($option as $item)
                                            @if($loop->first)
                                                <tr>
                                                    <th>{{__('dashboard.Option')}}</th>
                                                    <th>{{__('dashboard.Marked price')}}</th>
                                                    <th>{{__('dashboard.Sell price')}}</th>
                                                    <th>{{__('dashboard.Discount')}}</th>
                                                    <th>{{__('dashboard.Quantity')}}</th>
                                                    <th colspan="2">{{__('dashboard.Action')}}</th>
                                                </tr>
                                            @endif

                                            <tr class="options-form-data">
                                                <td>
                                                    <input type="hidden" name="option_id" value="{{$item->id}}">
                                                    <input type="text" name="size" class="form-control"
                                                           value="{{$item->size}}"></td>
                                                <td><input type="number" min="0" name="marked_price"
                                                           class="form-control"
                                                           value="{{$item->marked_price}}"
                                                           required></td>
                                                <td><input type="number" min="0" name="sell_price" class="form-control"
                                                           value="{{$item->sell_price}}"
                                                           required></td>
                                                <td><input type="number" min="0" max="99" name="discount_price"
                                                           class="form-control"
                                                           value="{{$item->discount}}"
                                                           required>
                                                </td>
                                                <td><input type="number" min="0" name="quantity" class="form-control"
                                                           value="{{$item->quantity}}" required>
                                                </td>
                                                <td colspan="2">
                                                    <span class="options-message-update" style="display: none"></span>
                                                    <form action="{{route('admin-delete-product-variant-post',$item->id)}}"
                                                          method="post">
                                                        {{csrf_field()}}
                                                        <button type="submit" style="width: 50px" class="btn red"
                                                                onclick="return confirm('Are you sure?')">
                                                            <i class="fa fa-trash"></i></button>
                                                    </form>
                                                    <a class="btn blue admin-options-form-update"><i
                                                                class="fa fa-save"></i>{{__('dashboard.Update')}}</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="7"></td>
                                        </tr>
                                    @endforeach
                                </table>
                                <form action="{{route('admin-add-product-variant',$product->id)}}" method="post">
                                    {{csrf_field()}}
                                    <table class="table">
                                        <tr>
                                            <th colspan="7">{{__('dashboard.New Options')}}</th>
                                        </tr>
                                        <tbody class="options-table-body">
                                        <tr class="options-table-show" style="display: none">
                                            <th style="width: 15%">{{__('dashboard.Color Family')}}</th>
                                            <th>{{__('dashboard.Size')}}</th>
                                            <th>{{__('dashboard.Marked price')}}</th>
                                            <th>{{__('dashboard.Sell price')}}</th>
                                            <th>{{__('dashboard.Discount')}}</th>
                                            <th>{{__('dashboard.Quantity')}}</th>
                                            <th>{{__('dashboard.Action')}}</th>
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
                                            class="options-table-show btn btn-primary btn-lg"
                                            style="display: none"> {{__('dashboard.save new options')}}</button>
                                </form>
                                <br>
                                <br>
                                <br>
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
@stop