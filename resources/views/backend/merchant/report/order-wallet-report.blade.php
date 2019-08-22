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
                                <h1> {{__('dashboard.Order Wallet Report')}}
                                </h1>
                            </div>

                        </div>
                    </div>
                    <div class="container">
                        <div class="portlet light">
                            <div class="portlet-body">

                                <form action="{{route('wallet-reportmerchant')}}">
                                    <div class="col-md-3"></div>
                                    <div class="col-md-6 ">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">{{__('dashboard.Start Date')}}</label>
                                                <input type="text" class="form-control datepicker" name="sd">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">{{__('dashboard.End Date')}}</label>
                                                <input type="text" class="form-control datepicker" name="ed">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <br>
                                            <button type="submit"
                                                    class="btn blue">{{__('dashboard.Generate Report')}}</button>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                    </div>
                                </form>

                                <br>
                                <table class="table table-striped table-bordered table-hover dataTable dtr-inline"
                                       id="sample_2">
                                    <thead>
                                    <tr>
                                        <th>{{__('dashboard.SN')}}</th>
                                        <th>{{__('dashboard.Product')}}</th>
                                        <th>{{__('dashboard.Invoice')}}</th>
                                        <th>{{__('dashboard.Order Date')}}</th>
                                        <th>{{__('dashboard.Delivery')}}</th>
                                        <th>{{__('dashboard.Net Amount')}}</th>
                                        <th>{{__('dashboard.Admin Amount')}}</th>
                                        <th>{{__('dashboard.Pay Method')}}</th>
                                        <th>{{__('dashboard.Status')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($reports as $key=>$report)
                                        <tr>
                                            <td>{{++$key}}</td>
                                            <td>
                                                {{$report->getProduct->name}}
                                                {{($report->getProductVariant !==null)?('[ '.$report->getProductVariant->name.' ]'):''}}
                                            </td>
                                            <td>
                                                {{$report->invoice}}
                                            </td>
                                            <td>
                                                {{$report->getOrder->order_date}}
                                            </td>
                                            <td>
                                                @if($report->deliver_date ===null)
                                                    {{__('dashboard.Processing')}}
                                                @else
                                                    {{__('dashboard.Delivered')}} - {{$report->deliver_date}}
                                                @endif
                                            </td>
                                            <td class="text-right">
                                                $ {{$report->sell_price * $report->quantity}}
                                            </td>
                                            <td class="text-right">
                                                @if($report->getShoppingLog !==null)
                                                    $ {{$report->getShoppingLog->admin_amount}}
                                                @else
                                                    Delivery Pending
                                                @endif

                                            </td>
                                            <td>
                                                @if(strpos($report->getOrder->payment_method,'ecash') && strpos($report->getOrder->payment_method,'evoucher'))
                                                    {{__('dashboard.E-cash / E-voucher')}}
                                                @elseif(strpos($report->getOrder->payment_method,'ecash'))
                                                    {{__('dashboard.E-cash')}}
                                                @else
                                                    {{__('dashboard.Cash on Delivery')}}
                                                @endif
                                            </td>
                                            <td>
                                                @if($report->getOrderStatus->key =='process')
                                                    <i class="badge badge-warning">{{$report->getOrderStatus->name}}</i>
                                                @elseif($report->getOrderStatus->key =='deliver')
                                                    <i class="badge badge-success">{{$report->getOrderStatus->name}}</i>
                                                @else
                                                    <i class="badge badge-danger">{{$report->getOrderStatus->name}}</i>
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
        <!-- END CONTAINER -->
    </div>
@endsection

@section('stylesheets')
    <style>
        .dataTables_wrapper .dataTables_filter {
            display: block !important;
        }
        .dt-buttons {
            display: block !important;
        }
        .buttons-print, .buttons-copy, .buttons-pdf {
            display: none !important;
        }
    </style>
@stop

@section('scripts')
    <script>
        var currentTime = new Date();
        $('input[name="sd"].datepicker').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            timePicker: false,
            startDate: "{!! \Carbon\Carbon::parse($sd)->format('dd-MMM-YYYY') !!}",
            // moment().format('DD-MM') + '-' + Number(moment().format('YYYY')),
            locale: {
                format: 'DD-MMM-YYYY',
            },
        });
        $('input[name="ed"].datepicker').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            timePicker: false,
            startDate: "{!! \Carbon\Carbon::parse($ed)->format('dd-MMM-YYYY') !!}",
            // moment().format('DD-MM') + '-' + Number(moment().format('YYYY')),
            locale: {
                format: 'DD-MMM-YYYY',
            },
        });
    </script>
@endsection