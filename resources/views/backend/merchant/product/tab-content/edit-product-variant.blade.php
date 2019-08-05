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
                                <li class="active" style="border-top: 1px solid red">
                                    <a href="{{route('variant-edit-product-merchant',$product->slug)}}"> {{__('dashboard.Product Options')}} </a>
                                </li>
                                <li class="">
                                    <a href="{{route('image-edit-product-merchant',$product->slug)}}"> {{__('dashboard.Images')}} </a>
                                </li>
                            </ul>
                            <div>
                                <table class="table table-borderless">

                                    @foreach($options as $color_id=>$option)
                                        <tr>
                                            <th rowspan="{{count($option) +3}}">
                                                @if($color_image = \App\Models\ColorImage::where('color_id',$color_id)->where('product_id',$product->id)->first())
                                                    <img src="{{asset('image/products/color/'.$color_image->image)}}"
                                                         alt=" " style="height: 120px">
                                                @else
                                                    <span class="lead">No image</span>
                                                @endif
                                            </th>
                                            <th colspan="6">
                                                <h4>{{__('dashboard.Color Family')}}
                                                    : {{\App\Models\Color::find($color_id)->name??' - '}}
                                                    <span style="height:50px;width:50px;background:{{\App\Models\Color::find($color_id)->color_code??''}}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                                </h4></th>
                                            <td colspan="2">
                                                <a class="btn blue action-button add-modal image-color-add-button"
                                                   data-toggle="modal" data-target="#addModal"
                                                   data-color="{{$color_id}}"
                                                   data-product="{{$product->id}}"> {{__('dashboard.Update Color image')}}</a>
                                            </td>
                                        </tr>

                                        @foreach($option as $item)
                                            @if($loop->first)
                                                <tr>
                                                    <th>{{__('dashboard.Size')}}</th>
                                                    <th>{{__('dashboard.Marked price')}}</th>
                                                    <th>{{__('dashboard.Sell price')}}</th>
                                                    <th>{{__('dashboard.Discount')}}</th>
                                                    <th class="check-delivery-option"
                                                        colspan="2">{{__('dashboard.Stock')}}
                                                        /{{__('dashboard.Quantity')}}</th>
                                                    <th colspan="2">{{__('dashboard.Action')}}</th>
                                                </tr>
                                            @endif

                                            <tr class="options-form-data">
                                                <td>
                                                    <input type="hidden" name="option_id" value="{{$item->id}}">
                                                    <input type="text" name="size" class="form-control"
                                                           value="{{$item->size}}"></td>
                                                <td><input type="text" min="0" name="marked_price"
                                                           class="form-control marked-price"
                                                           value="{{($item->marked_price)}}">
                                                </td>
                                                <td><input type="text" min="0" name="sell_price"
                                                           class="form-control sell-price"
                                                           value="{{($item->sell_price)}}"
                                                           required></td>
                                                <td><input type="text" min="0" max="99" name="discount_price"
                                                           class="form-control discount"
                                                           value="{{$item->discount}}">
                                                </td>
                                                <td class="check-delivery-option">
                                                    <label>ManageStock
                                                        <input type="checkbox" name="stock_option"
                                                               class="checkbox-stock"
                                                               value="{{($item->stock_option)?'true':'false'}}" {{($item->stock_option)?'checked':''}}>
                                                    </label>
                                                </td>
                                                <td class="check-delivery-option">
                                                    @if($item->stock_option)
                                                        <span class="quantity">Total quantity</span>
                                                    @else
                                                        <span class="quantity">Max Purchase Limit</span>
                                                    @endif
                                                    <input type="number" min="0" name="quantity"
                                                           class="form-control"
                                                           value="{{$item->quantity}}" required>
                                                </td>
                                                <td>
                                                            <span class="options-message-update"
                                                                  style="display: none"></span>
                                                    <a class="btn blue options-form-update"><i
                                                                class="fa fa-save"></i>{{__('dashboard.Update')}}
                                                    </a>
                                                </td>
                                                <td>
                                                    <form action="{{route('delete-product-variant-post',$item->id)}}"
                                                          method="post">
                                                        {{csrf_field()}}
                                                        <button type="submit" style="width: 50px"
                                                                class="btn red"
                                                                onclick="return confirm('Are you sure?')">
                                                            <i class="fa fa-trash"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="7"></td>
                                        </tr>
                                    @endforeach
                                </table>
                                <form action="{{route('add-product-variant-merchant',$product->id)}}"
                                      method="post">
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
                                            <th class="check-delivery-option">{{__('dashboard.Stock')}}
                                                /{{__('dashboard.Quantity')}}</th>
                                            <th>{{__('dashboard.Action')}}</th>
                                        </tr>
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <td colspan="7">
                                                <button class="form-control btn-primary-outline options-add-btn">
                                                    <i
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


                            <!-- Add Modal -->
                            <div class="modal fade" id="addModal" tabindex="-1"
                                 role="dialog" aria-labelledby="editModalLabel"
                                 aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content" style="width: 100vh">
                                        <div class="modal-header">
                                            <h4 class="modal-title">{{__('dashboard.Update Color image')}}</h4>
                                            <button type="button" class="close"
                                                    data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">

                                            <form action="{{route('update-product-variant-image')}}" method="post"
                                                  enctype="multipart/form-data">
                                                {{csrf_field()}}
                                                <input type="hidden" name="color_id" id="color_id">
                                                <input type="hidden" name="product_id" id="product_id">

                                                <input type="file" name="image" class="form-control image"
                                                       required>
                                                <div>
                                                    <input type="hidden" name="x1" value=""/>
                                                    <input type="hidden" name="y1" value=""/>
                                                    <input type="hidden" name="h1" value=""/>
                                                    <input type="hidden" name="w1" value=""/>
                                                    <br>
                                                    <button type="submit"
                                                            class="btn blue">{{__('dashboard.Update Color image')}}</button>
                                                    <div class="row mt-5">
                                                        <br>
                                                        <br>
                                                        <br>
                                                        <p><img id="previewimage" style="display:none;"/></p>
                                                        @if(session('path'))
                                                            <img src="{{ session('path') }}"/>
                                                        @endif
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <br>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">{{__('dashboard.Close')}}
                                            </button>
                                        </div>
                                    </div>
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
    <script src="{{URL::asset('backend/assets/pages/scripts/ui-modals.min.js')}}"
            type="text/javascript"></script>
    <script src="{{URL::asset('backend/js/merchant/create-product-merchant.js')}}"
            type="text/javascript"></script>
    <script src="{{ asset('backend/plugin/scripts/jquery.imgareaselect.min.js') }}"></script>
    <script src="{{ asset('backend/js/merchant/imageselect-config.js') }}"></script>

    <script>
        $('.image-color-add-button').on('click', function () {
            $('#product_id').val($(this).data('product'));
            $('#color_id').val($(this).data('color'));
        });
    </script>
@stop

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('backend/plugin/css/imgareaselect-default.css') }}">
@endsection