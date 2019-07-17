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
                            <div class="row">
                                <div class="page-title">
                                    <h1>{{__('dashboard.Order List')}}
                                        <small></small>
                                    </h1>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- END PAGE HEAD-->
                    <!-- BEGIN PAGE CONTENT BODY -->
                    <div class="page-content">
                        <div class="container">
                        @include('fragments.message')
                        <!-- BEGIN PAGE CONTENT INNER -->
                            <div class="page-content-inner">

                                <div class="portlet light ">

                                    <div class="tabbable-custom nav-justified">
                                        <ul class="nav nav-tabs nav-justified">

                                            <li class="active">
                                                <a href="#tab_1_1_1"
                                                   data-toggle="tab"> {{__('dashboard.All Orders')}} </a>
                                            </li>
                                            <li class="">
                                                <a href="#tab_1_1_2"
                                                   data-toggle="tab">{{__('dashboard.Pending Orders')}}</a>
                                            </li>
                                            <li class="">
                                                <a href="#tab_1_1_3"
                                                   data-toggle="tab">{{__('dashboard.Completed Orders')}}</a>
                                            </li>
                                        </ul>

                                        <div class="tab-content">

                                            <div class="tab-pane active" id="tab_1_1_1">
                                                <table class="table table-striped table-bordered table-hover dataTable dtr-inline"
                                                       id="sample_2">
                                                    <thead>
                                                    <tr>
                                                        <th> {{__('dashboard.Invoice')}}#
                                                        </th>
                                                        <th>{{__('dashboard.Order Date')}}
                                                        </th>
                                                        <th>{{__('dashboard.Buyer Name')}}
                                                        </th>
                                                        <th style="min-width: 200px">{{__('dashboard.Product')}}
                                                        </th>
                                                        <th>{{__('dashboard.Action')}}
                                                        </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($orders as $k=>$order)
                                                        @include('backend.admin.merchant-master.table-order-loop')
                                                    @endforeach

                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="tab-pane " id="tab_1_1_2">
                                                <table class="table table-striped table-bordered table-hover dataTable dtr-inline"
                                                       id="sample_3">
                                                    <thead>
                                                    <tr>
                                                        <th> {{__('dashboard.Invoice')}}#
                                                        </th>
                                                        <th>{{__('dashboard.Order Date')}}
                                                        </th>
                                                        <th>{{__('dashboard.Buyer Name')}}
                                                        </th>
                                                        <th style="min-width: 200px">{{__('dashboard.Product')}}
                                                        </th>
                                                        <th>{{__('dashboard.Action')}}
                                                        </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($p_orders as $k=>$order)
                                                        @include('backend.admin.merchant-master.table-order-loop')
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="tab-pane " id="tab_1_1_3">
                                                <table class="table table-striped table-bordered table-hover dataTable dtr-inline"
                                                       id="sample_4">
                                                    <thead>
                                                    <tr>
                                                        <th> {{__('dashboard.Invoice')}}#
                                                        </th>
                                                        <th>{{__('dashboard.Order Date')}}
                                                        </th>
                                                        <th>{{__('dashboard.Buyer Name')}}
                                                        </th>
                                                        <th style="min-width: 200px">{{__('dashboard.Product')}}
                                                        </th>
                                                        <th>{{__('dashboard.Action')}}
                                                        </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($d_orders as $k=>$order)
                                                        @include('backend.admin.merchant-master.table-order-loop')
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- END PORTLET -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function () {
            $('#sample_4').dataTable();
            $('#sample_5').dataTable();
        });
    </script>
@endsection
@section('stylesheets')
    <style>
        .dataTables_wrapper .dataTables_filter {
            display: block !important;
        }
    </style>
@endsection