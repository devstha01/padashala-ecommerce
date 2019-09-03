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
                                <h1>Standard Products</h1>
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
                                    <th>Share</th>
                                    <th>Add</th>
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
                                            <div class="product-highlight"><?php echo htmlspecialchars_decode($product->detail)?></div>
                                        </td>
                                        <td>
                                            @if($product->share_percentage)
                                                {{$product->share_percentage+0}}%
                                            @else
                                                {{$product->getCategory->share_percentage+0}}%
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{route('create-product-merchant-standard-post',$product->id)}}"
                                                  method="post">
                                                {{csrf_field()}}
                                                <button class="btn blue" type="submit">
                                                    <i class="fa fa-plus"> Add</i></button>
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
