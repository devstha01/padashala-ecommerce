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
                                <h1> {{__('dashboard.Monthly Bonus Report')}}
                                </h1>
                            </div>

                        </div>
                    </div>
                    <div class="container">
                    <div class="portlet light">
                        <div class="portlet-body">

                            {{--<form action="{{route('monthly-bonus-report')}}">--}}
                            {{--<div class="col-md-3"></div>--}}
                            {{--<div class="col-md-6 ">--}}
                            {{--<div class="col-md-4">--}}
                            {{--<div class="form-group">--}}
                            {{--<label class="control-label">{{__('dashboard.Start Date')}}</label>--}}
                            {{--<input type="text" class="form-control datepicker" name="start_date">--}}
                            {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="col-md-4">--}}
                            {{--<div class="form-group">--}}
                            {{--<label class="control-label">{{__('dashboard.End Date')}}</label>--}}
                            {{--<input type="text" class="form-control datepicker" name="end_date">--}}
                            {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="col-md-4">--}}
                            {{--<br>--}}
                            {{--<button type="submit"--}}
                            {{--class="btn blue">{{__('dashboard.Generate Report')}}</button>--}}
                            {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="col-md-3"></div>--}}
                            {{--</form>--}}

                            <form action="">
                                <div class="col-md-3"></div>
                                <div class="col-md-6">
                                    <div class="col-md-4 col-sm-3 text-right">
                                        <label class="control-label">{{__('dashboard.Select Month')}} : </label>
                                    </div>
                                    <div class="col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control datepicker" name="month">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-3">
                                        <button type="submit"
                                                class="btn blue">{{__('dashboard.Generate Report')}}</button>
                                        <br>
                                    </div>
                                </div>
                                <div class="col-md-3"></div>
                            </form>
                            <br>
                            <table class="table table-striped table-bordered table-hover dataTable dtr-inline"
                                   id="sample_2">
                                <thead>
                                <tr>
                                    <th>{{__('dashboard.SN')}}</th>
                                    {{--                                    <th>{{__('dashboard.Member Id')}}</th>--}}
                                    <th>{{__('dashboard.Item')}}</th>
                                    <th>{{__('dashboard.Merchant')}}</th>
                                    <th>{{__('dashboard.Net Monthly Bonus')}}</th>
                                    <th>{{__('dashboard.Hk Bonus')}}</th>
                                    <th>{{__('dashboard.Asia Bonus')}}</th>
                                    <th>{{__('dashboard.Top Shopper Bonus')}}</th>
                                    <th>{{__('dashboard.Date')}}</th>
                                    <th>{{__('dashboard.Bonus Distribution')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($reports as $key=>$report)
                                    <tr>
                                        <td>{{++$key}}</td>
                                        <td>
                                            {{$report->getOrderItem->getProduct->name}}
                                            @if($report->getOrderItem->getProductVariant !==null)
                                                [{{$report->getOrderItem->getProductVariant->name}}]
                                            @endif
                                            <a href="{{url('product/'.$report->getOrderItem->getProduct->slug)}}"
                                               class="fa fa-eye pull-right"></a>
                                        </td>
                                        <td>
                                            {{$report->getOrderItem->getProduct->getBusiness->name}}

                                        </td>
                                        <td class="text-right">{{$report->bonus}}</td>
                                        <td class="text-right">{{$report->hk}}</td>
                                        <td class="text-right">{{$report->asia}}</td>
                                        <td class="text-right">{{$report->top_shopper}}</td>
                                        <td>{{$report->created_at}}</td>
                                        <td class="text-right">{{($report->status)?__('dashboard.Pending'):__('dashboard.Done')}}</td>
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
    </style>
@stop

@section('scripts')
    <script>
        {{--var currentTime = new Date();--}}
        {{--$('input[name="start_date"].datepicker').daterangepicker({--}}
        {{--singleDatePicker: true,--}}
        {{--showDropdowns: true,--}}
        {{--timePicker: true,--}}
        {{--startDate: "{!! $sd??Carbon\Carbon::now()->subDays(30)->format('dd-MMM-YYYY  HH:ii') !!}",--}}
        {{--// moment().format('DD-MM') + '-' + Number(moment().format('YYYY')),--}}
        {{--locale: {--}}
        {{--format: 'DD-MMM-YYYY HH:mm A',--}}
        {{--},--}}
        {{--});--}}
        {{--$('input[name="end_date"].datepicker').daterangepicker({--}}
        {{--singleDatePicker: true,--}}
        {{--showDropdowns: true,--}}
        {{--timePicker: true,--}}
        {{--startDate: "{!! $ed??Carbon\Carbon::now()->format('dd-MMM-YYYY  HH:ii') !!}",--}}
        {{--// moment().format('DD-MM') + '-' + Number(moment().format('YYYY')),--}}
        {{--locale: {--}}
        {{--format: 'DD-MMM-YYYY HH:mm A',--}}
        {{--},--}}
        {{--});--}}

        $('input[name="month"].datepicker').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            startDate: "{!! $month??Carbon\Carbon::now()->format('MMM-YYYY') !!}",
            locale: {
                format: 'MMM-YYYY',
            },
        });

    </script>
@endsection