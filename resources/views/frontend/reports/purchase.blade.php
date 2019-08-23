@extends('frontend.layouts.app')
@section('content')
    <main class="main">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">

                    {{--                    @include('frontend.reports.report-nav')--}}
                    <br>
                    <h3>{{__('front.Product Purchase Report')}} </h3>

                    <table class="table border table-hover dataTable dtr-inline"
                           id="sample_2">
                        <thead>
                        <tr>
                            <th>{{__('dashboard.SN')}}</th>
                            <th>{{__('dashboard.Order Date')}}</th>
                            <th>{{__('dashboard.Deliver Date')}}</th>
                            <th>{{__('dashboard.Product')}}</th>
                            <th>{{__('dashboard.Quantity')}}</th>
                            <th>{{__('dashboard.Net Amount')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($reports as $key=>$report)
                            <tr>
                                <td>{{++$key}}</td>
                                <td>{{$report->getOrder->order_date}}</td>
                                <td>{{$report->deliver_date??' - '}}</td>
                                <td>{{$report->getProduct->name}}</td>
                                <td>{{$report->quantity}}</td>
                                <td>{{($report->quantity * $report->sell_price)+$report->net_tax}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
        <div class="mb-5"></div><!-- margin -->
    </main>
@endsection

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('frontend/plugin/DataTables/datatables.css')}}">
@endsection

@section('scripts')
    <script src="{{asset('frontend/plugin/DataTables/datatables.js')}}"></script>
    <script>
        $(function () {
            $('#sample_2').DataTable();
        })
    </script>
@endsection