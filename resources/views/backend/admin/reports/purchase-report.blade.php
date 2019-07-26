@extends('backend.layouts.master')

@section('content')
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
                                <h1> {{__('front.Product Purchase Report')}}
                                </h1>
                            </div>

                        </div>
                    </div>


                    <div class="container">
                        <div class="portlet light">
                            <div class="portlet-body">
                                <table class="table table-striped table-bordered table-hover dataTable dtr-inline"
                                       id="sample_1">
                                    <thead>
                                    <tr>
                                        <th>{{__('dashboard.SN')}}</th>
                                        <th>{{__('dashboard.Buyer')}}</th>
                                        <th>{{__('dashboard.Order Date')}}</th>
                                        <th>{{__('dashboard.Deliver Date')}}</th>
                                        <th>{{__('dashboard.Product')}}</th>
                                        <th>{{__('dashboard.Quantity')}}</th>
                                        <th>{{__('dashboard.Net Amount')}}</th>
                                        <th>{{__('dashboard.Admin Share')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($reports as $key=>$report)
                                        <tr>
                                            <td>{{++$key}}</td>
                                            <td>{{$report->getOrder->getUser->user_name}}</td>
                                            <td>{{$report->getOrder->order_date}}</td>
                                            <td>{{$report->deliver_date??' - '}}</td>
                                            <td>{{$report->getProduct->name}}</td>
                                            <td>{{$report->quantity}}</td>
                                            <td>{{$report->quantity * $report->sell_price}}</td>
                                            <td>
                                                @if($report->getShoppingLog !==null)
                                                    {{$report->getShoppingLog->admin_amount}}
                                                @else
                                                    Delivery Pending
                                                @endif
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
@endsection

@section('stylesheets')
    <style>
        .dataTables_wrapper .dataTables_filter {
            display: block !important;
        }
    </style>
@stop