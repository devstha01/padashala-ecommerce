@extends('backend.layouts.master')

@section('content')
    <div class="page-wrapper-row full-height">
        <div class="page-wrapper-middle">
            <div class="page-container">
                <div class="container">
                    <h3>{{__('dashboard.Products')}}</h3>
                    @if($term !== null)
                        <b>{{__('dashboard.Search results for')}} '{{$term}}'</b>
                    @endif
                    <div class="pull-right">
                        <div class="form-group">

                            <form action="{{route('view-product-merchant')}}" method="get" id="search-product-form">
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control" name="term" placeholder="Search for..."
                                           id="search-product" autocomplete="off">
                                    <span class="input-group-btn">
                                    <button class="btn green" type="submit">{{__('dashboard.Go')}}!</button>
                                </span>
                                </div>
                            </form>

                            <div id="search-product-list">
                            </div>


                        </div>
                    </div>
                    @include('fragments.message')
                    <table class="table table-hover bg-white">
                        <tr>
                            <th width="5%">#</th>
                            <th width="10%">{{__('dashboard.Image')}}</th>
                            <th width="40%">{{__('dashboard.Name')}} / {{__('dashboard.Detail')}}</th>
                            {{--<th width="20%">business / merchant</th>--}}
                            <th>{{__('dashboard.Action')}}</th>
                            <th>{{__('dashboard.Feature Product')}}</th>
                            <th colspan="2">{{__('dashboard.Lang')}}</th>
                            <th>{{__('dashboard.Remove')}}</th>
                        </tr>
                        <?php
                        $page = $_GET['page'] ?? '1';?>
                        @forelse($products as $key=>$product)
                            <tr>
                                <td>{{++$key + (25*($page-1))}}</td>
                                <td>
                                    @if(!empty($product->featured_image))
                                        <img src="{{asset('image/products/'.$product->featured_image)}}" alt="image"
                                             height="60px">
                                    @else
                                        {{__('dashboard.No image available')}}.
                                    @endif
                                </td>
                                <td>
                                    <b>{{$product->name}}</b>
                                    <br>
                                    <span>{{str_limit($product->detail??'',100)}}</span>
                                </td>

                                {{--<td>--}}
                                {{--{{$product->getBusiness->name}}--}}
                                {{--| {{$product->getBusiness->getMerchant->name}} {{$product->getBusiness->getMerchant->surname}}--}}
                                {{--</td>--}}
                                <td>
                                    <a title="detail" href="{{route('edit-product-merchant',$product->slug)}}"><i
                                                class="fa fa-edit btn blue"> {{__('dashboard.Edit')}}</i></a>
                                </td>
                                <td>
                                    @if($product->is_featured==1)
                                        <i class="fa fa-check-circle text-success"> {{__('dashboard.Featured')}}</i>
                                    @elseif(!\App\Models\FeatureProduct::where('product_id',$product->id)->where('flag',0)->first())
                                        <a href="{{route('merchant-featured-product-request',$product->id)}}"
                                           class="fa fa-question-circle text-warning"> {{__('dashboard.Request')}} </a>
                                    @else
                                        <i class="fa fa-spinner text-danger"> {{__('dashboard.Processing')}}</i>
                                    @endif

                                </td>

                                <td>
                                    <a href="{{route('edit-product-ch-merchant',$product->slug)}}"><i
                                                class="fa fa-language btn btn-info">{{__('dashboard.CH')}}</i></a>
                                </td>
                                <td>
                                    <a href="{{route('edit-product-tr-ch-merchant',$product->slug)}}"><i
                                                class="fa fa-language btn btn-info">{{__('dashboard.TR-CH')}}</i></a>
                                </td>
                                <td>
                                    <form action="{{route('delete-product-merchant',$product->id)}}" method="post">
                                        {{csrf_field()}}
                                        <button class="btn red" type="submit"
                                                onclick="return confirm('Are you sure you want to remove this product?')">
                                            <i class="fa fa-trash"> {{__('dashboard.Delete')}}</i></button>
                                    </form>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    @if(count($product->getProductVariant->where('status',1))!==0)
                                        <b class="pull-right"> {{__('dashboard.Variants')}} : </b>
                                    @endif
                                </td>
                                <td>
                                    @forelse($product->getProductVariant->where('status',1) as $variant)
                                        {{++$loop->index}}) {{$variant->name}} <i class="pull-right">{{__('dashboard.Quantity')}}
                                            - {{$variant->quantity}}</i>
                                        <br>
                                    @empty
                                        {{__('dashboard.No variants')}}! <i class="pull-right">{{__('dashboard.Quantity')}} - {{$product->quantity}}</i>
                                    @endforelse
                                </td>
                                <td colspan="5"></td>
                            </tr>
                        @empty
                        @endforelse
                    </table>
                    @if($term === null)
                        {!! $products->render() !!}
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop

@section('stylesheets')
    <style>
        #search-product-list {
            position: absolute;
            background: white;
            width: 200px;
            height: 0;
            max-height: 200px;
            overflow-y: scroll;
        }

        #search-product-list a {
            margin-top: 10px;
        }
    </style>
@stop

@section('scripts')
    <script src="{{URL::asset('backend/js/merchant/create-product-merchant.js')}}" type="text/javascript"></script>
@stop