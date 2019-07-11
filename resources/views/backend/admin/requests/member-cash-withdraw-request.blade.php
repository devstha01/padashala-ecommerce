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
                                <h1> {{__('dashboard.Member Cash Withdrawal Request')}}
                                </h1>
                            </div>

                        </div>
                    </div>

                    <div class="page-content">
                        <div class="container">
                            <section>
                                <div class="row">
                                    <div class="col-xs-12">
                                        @include('fragments.message')

                                        <div class="portlet light box">
                                            <div class="portlet-body box-body">

                                                <div class="table-reponsive table-scrollable">
                                                    <table class="table table-striped table-bordered table-hover dataTable dtr-inline" id="sample_2">
                                                        <thead>
                                                        <tr>
                                                            <th>S.No.</th>
                                                            <th>{{__('dashboard.Member Id')}}</th>
                                                            <th>{{__('dashboard.Contact Number')}}</th>
                                                            <th>{{__('dashboard.Amount')}}</th>
                                                            <th>{{__('dashboard.Bank Name')}}</th>
                                                            <th>{{__('dashboard.Account Name')}}</th>
                                                            <th>{{__('dashboard.Account No')}}</th>
                                                            <th>{{__('dashboard.Remarks')}}</th>
                                                            <th>{{__('dashboard.Status')}}</th>
                                                            <th>{{__('dashboard.Action')}}</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>

                                                        @foreach ($members as $key=>$member)
                                                            <tr>
                                                                <td>{{++$key}}</td>

                                                                <td>{{$member->getUser->user_name}}</td>
                                                                <td>{{$member->contact_number}}</td>
                                                                <td>{{$member->amount}}</td>
                                                                <td>{{$member->bank_name}}</td>
                                                                <td>{{$member->acc_name}}</td>
                                                                <td>{{$member->acc_number}}</td>
                                                                <td>{{$member->remarks}}</td>
                                                                <td>{{$member->status}}</td>
                                                                <td>
                                                                    <button type="button" class="btn btn-primary"><a
                                                                                href="{{route('admin-member-cash-withdraw-accept',$member->id)}}">{{__('dashboard.Accept')}}</a>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div><!-- /.row -->
                            </section><!-- /.content -->
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
