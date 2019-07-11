@extends('frontend.layouts.app')
@section('content')
    <main class="main">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">

                    @include('frontend.reports.report-nav')
                    <br>
                    <h3>{{__('dashboard.Wallet Transfer Report')}} </h3>

                    <table class="table border table-hover dataTable dtr-inline"
                           id="sample_2">
                        <thead>
                        <tr>
                            <th>{{__('dashboard.SN')}}</th>
                            <th>{{__('dashboard.From Customer')}}</th>
                            <th>{{__('dashboard.To Customer')}}</th>
                            <th>{{__('dashboard.Wallet')}}</th>
                            <th>{{__('dashboard.Amount')}}</th>
                            <th>{{__('dashboard.Remarks')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($reports as $key=>$report)
                            <tr>
                                <td>{{++$key}}</td>
                                <td>{{$report->getFrom->user_name}}</td>
                                <td>{{$report->getTo->user_name}}</td>
                                <td>{{$report->getWallet->detail}}</td>
                                <td>{{$report->amount}}</td>
                                <td>{{$report->remarks}}</td>
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