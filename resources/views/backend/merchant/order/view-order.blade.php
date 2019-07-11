@extends('backend.layouts.master')

@section('content')
    <div class="page-wrapper-row full-height">
        <div class="page-wrapper-middle">
            <div class="page-container">
                <div class="container">
                    <h3>{{__('dashboard.Orders')}}</h3>

                    <div class="portlet light">

                        <div class="tabbable-custom nav-justified">
                            <ul class="nav nav-tabs nav-justified">
                                <li class="{{session('order-tab') ==='all'?'active':''}}">
                                    <a href="#tab_1_1_1" data-toggle="tab"> {{__('dashboard.All Orders')}} </a>
                                </li>
                                <li class="{{session('order-tab') ==='pending'?'active':''}}">
                                    <a href="#tab_1_1_2" data-toggle="tab">{{__('dashboard.Pending Orders')}}</a>
                                </li>
                                <li class="">
                                    <a href="#tab_1_1_3" data-toggle="tab">{{__('dashboard.Completed Orders')}}</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane {{session('order-tab') ==='all'?'active':''}}" id="tab_1_1_1">
                                    <table class="table table-striped table-bordered table-hover dataTable dtr-inline"
                                           id="sample_1" role="grid" aria-describedby="sample_1_info">
                                        <thead>
                                        <tr>
                                            <th style="width: 20%">
                                                {{__('dashboard.Invoice')}}#
                                            </th>
                                            <th style="width: 15%;">{{__('dashboard.Order Date')}}</th>
                                            <th style="width: 20%">{{__('dashboard.Buyer Name')}}</th>
                                            <th style="width: 40%">{{__('dashboard.Product')}}</th>
                                            <th style="width: 5%">{{__('dashboard.Action')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($orders as $k=>$order)
                                            @include('backend.merchant.order.table-row-loop')
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane {{session('order-tab') ==='pending'?'active':''}}" id="tab_1_1_2">
                                    <table class="table table-striped table-bordered table-hover dataTable dtr-inline"
                                           id="sample_2" role="grid" aria-describedby="sample_2_info">
                                        <thead>
                                        <tr role="row">
                                            <th style="width: 20%">
                                                {{__('dashboard.Invoice')}}#
                                            </th>
                                            <th style="width: 15%;">{{__('dashboard.Order Date')}}</th>
                                            <th style="width: 20%">{{__('dashboard.Buyer Name')}}</th>
                                            <th style="width: 35%">{{__('dashboard.Product')}}</th>
                                            <th style="width: 5%">{{__('dashboard.Action')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($p_orders as $k=>$order)
                                            @include('backend.merchant.order.table-row-loop')
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane " id="tab_1_1_3">
                                    <table class="table table-striped table-bordered table-hover dataTable dtr-inline"
                                           id="sample_3" role="grid" aria-describedby="sample_3_info">
                                        <thead>
                                        <tr role="row">
                                            <th style="width: 20%">
                                                {{__('dashboard.Invoice')}}#
                                            </th>
                                            <th style="width: 15%;">{{__('dashboard.Order Date')}}</th>
                                            <th style="width: 20%">{{__('dashboard.Buyer Name')}}</th>
                                            <th style="width: 35%">{{__('dashboard.Product')}}</th>
                                            <th style="width: 5%">{{__('dashboard.Action')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($d_orders as $k=>$order)
                                            @include('backend.merchant.order.table-row-loop')
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
    </div>
@stop

@section('stylesheets')
    <style>
        .dataTables_wrapper .dataTables_filter {
            display: block !important;
        }
    </style>
@endsection