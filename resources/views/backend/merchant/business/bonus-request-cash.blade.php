@extends('backend.layouts.master')

@section('content')

    <style>
        .table.overview tr {
            border: 0;
        }

        .table.overview tr td {
            vertical-align: middle;
            border: 0;
        }

        .table.overview {
            margin-bottom: 0;
        }

        .table.overview > tbody > tr > td, .table.overview > tbody > tr > th, .table.overview > tfoot > tr > td, .table.overview > tfoot > tr > th, .table.overview > thead > tr > td, .table.overview > thead > tr > th {
            padding: 0;
        }
    </style>

    <div class="page-wrapper-row full-height">
        <div class="page-wrapper-middle">
            <!-- BEGIN CONTAINER -->
            <div class="page-container">
                <!-- BEGIN CONTENT -->
                <div class="page-content-wrapper">
                    <!-- BEGIN CONTENT BODY -->
                    <!-- BEGIN PAGE HEAD-->
                    <div class="page-head">
                        <div class="container">
                            <!-- BEGIN PAGE TITLE -->
                            <div class="page-title">
                                <h1>{{ __('dashboard.Wallet Transfer Request') }}
                                </h1>
                            </div>
                        </div>
                    </div>

                    <div class="container">
                        @include('backend.merchant.merchant-wallet-card')

                        @include('fragments.message')
                        <div class="row">
                            <div class="col-md-12">
                                <div class="portlet light">
                                    <h3>{{__('dashboard.Admin Bonus Request')}}</h3>
                                    <table class="table table-striped table-bordered table-hover dataTable dtr-inline"
                                           id="sample_2">
                                        <thead>
                                        <tr>
                                            <th>{{__('dashboard.Invoice')}}</th>
                                            <th>{{__('dashboard.Product')}}</th>
                                            <th>{{__('dashboard.Variant')}}</th>
                                            <th>{{__('dashboard.Pay Method')}}</th>
                                            <th>{{__('dashboard.Net Amount')}}</th>
                                            <th>{{__('dashboard.Admin Amount')}}</th>
                                            <th>{{__('dashboard.Action')}}</th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        @foreach($request as $key=>$req)
                                            <tr>
                                                <td>{{$req->getOrderItem->invoice}}</td>
                                                <td>{{$req->getOrderItem->getProduct->name}}</td>
                                                <td>{{$req->getOrderItem->getProductVariant->name??' - '}}</td>
                                                <td>{{__('dashboard.Cash on Delivery')}}</td>
                                                <td>$ {{$req->total}}</td>
                                                <td>$ {{$req->admin}}</td>
                                                <td>
                                                    {{ Form::open([
                                                'url' => 'merchant/payment/bonus-request-cash/'.$req->id,
                                                'class' => 'horizontal-form ajax-post-merchant',
                                                'method'=> 'POST'
                                                ])   }}
                                                    <button type="submit" class="btn blue"><i class="fa fa-money-bill"></i>
                                                        {{__('dashboard.Submit')}}
                                                    </button>
                                                    {{Form::close()}}
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


        </div>
        <!-- END CONTAINER -->
    </div>
@stop

@section('stylesheets')
    <style>
        .dataTables_wrapper .dataTables_filter {
            display: block !important;
        }
    </style>
@endsection