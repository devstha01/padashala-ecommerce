@extends('backend.layouts.master')

@section('content')
    <div class="page-wrapper-row full-height">
        <div class="page-wrapper-middle">
            <div class="page-container">
                <div class="page-content-wrapper">
                    <!-- BEGIN CONTENT BODY -->
                    <!-- BEGIN PAGE HEAD-->
                    <div class="page-head">
                        <div class="container">
                            <!-- BEGIN PAGE TITLE -->
                            <div class="page-title">
                                <h1>{{__('dashboard.Products')}}</h1>
                            </div>

                        </div>
                    </div>
                    <div class="container">
                        <div class="portlet light">

                            @include('fragments.message')
                            <table class="table table-striped table-bordered table-hover dataTable dtr-inline"
                                   id="sample_1">
                                <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th>{{__('dashboard.Image')}}</th>
                                    <th>{{__('dashboard.Name')}} / {{__('dashboard.Detail')}}</th>
                                    <th>{{__('dashboard.Action')}}</th>
                                    <th>{{__('dashboard.Feature Product')}}</th>
                                    <th>{{__('dashboard.Remove')}}</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($products as $key=>$product)
                                    <tr>
                                        <td>{{++$key}}</td>
                                        <td>
                                            @if(!empty($product->featured_image))
                                                <img src="{{asset('image/products/'.$product->featured_image)}}"
                                                     alt="image"
                                                     height="60px">
                                            @else
                                                {{__('dashboard.No image available')}}.
                                            @endif

                                        </td>
                                        <td>
                                            <b>{{$product->name}}</b>
                                            <hr>
                                            @forelse($product->getProductVariant->where('status',1) as $variant)
                                                @if($loop->index <3)
                                                    {{++$loop->index}}) {{$variant->name}} <i
                                                            class="pull-right">{{__('dashboard.Quantity')}}
                                                        - {{$variant->quantity}}</i>
                                                    <br>
                                                @endif
                                            @empty
                                                {{__('dashboard.No variants')}}! <i
                                                        class="pull-right">{{__('dashboard.Quantity')}}
                                                    - {{$product->quantity}}</i>
                                            @endforelse
                                        </td>
                                        <td>
                                            <a title="detail"
                                               href="{{route('edit-product-merchant',$product->slug)}}"><i
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

                                        {{--<td>--}}
                                        {{--<a href="{{route('edit-product-ch-merchant',$product->slug)}}"><i--}}
                                        {{--class="fa fa-language btn btn-info">{{__('dashboard.CH')}}</i></a>--}}
                                        {{--</td>--}}
                                        {{--<td>--}}
                                        {{--<a href="{{route('edit-product-tr-ch-merchant',$product->slug)}}"><i--}}
                                        {{--class="fa fa-language btn btn-info">{{__('dashboard.TR-CH')}}</i></a>--}}
                                        {{--</td>--}}
                                        <td>
                                            <form action="{{route('delete-product-merchant',$product->id)}}"
                                                  method="post">
                                                {{csrf_field()}}
                                                <button class="btn red" type="submit"
                                                        onclick="return confirm('Are you sure you want to remove this product?')">
                                                    <i class="fa fa-trash"> {{__('dashboard.Delete')}}</i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('stylesheets')
    <style>
        .dataTables_wrapper .dataTables_filter {
            display: block !important;
        }
    </style>

@stop
