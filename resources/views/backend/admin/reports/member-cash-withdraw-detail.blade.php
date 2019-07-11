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
                                <h1>{{__('dashboard.Cash Withdraw Detail')}}
                                </h1>
                            </div>

                        </div>
                    </div>

                    <div class="container">
                    <div class="portlet light">
                        <div class="portlet-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td>{{__('dashboard.Name')}}</td>
                                    <td>{{$report->getUser->name}} {{$report->getUser->surname}}</td>
                                    <td>{{__('dashboard.Withdraw Amount')}}</td>
                                    <td>{{$report->amount}}</td>
                                </tr>
                                <tr>
                                    <td>{{__('dashboard.Login Id')}}</td>
                                    <td>{{$report->getUser->user_name}}</td>
                                    <td>{{__('dashboard.Withdraw Date')}}</td>
                                    <td>{{$report->created_at}}</td>
                                </tr>
                                <tr>
                                    <td>{{__('dashboard.Contact Number')}}</td>
                                    <td>{{$report->getUser->contact_number}}</td>
                                    <td>{{__('dashboard.Updated By')}}</td>
                                    <td>{{$report->withdraw_date?$report->admin->name:' - '}}</td>
                                </tr>
                                <tr>
                                    <td>{{__('dashboard.Email')}}</td>
                                    <td>{{$report->getUser->email}}</td>
                                    <td>{{__('dashboard.Updated On')}}</td>
                                    <td>{{$report->withdraw_date??' - '}}</td>
                                </tr>
                                <tr>
                                    <td>{{__('dashboard.Marital Status')}}</td>
                                    <td>{{$report->getUser->marital_status}}</td>
                                    <td>{{__('dashboard.Remarks')}}</td>
                                    <td>{{$report->remarks}}</td>
                                </tr>
                                <tr>
                                    <td>{{__('dashboard.Gender')}}</td>
                                    <td>{{$report->getUser->gender}}</td>
                                    <td>{{__('dashboard.Status')}}</td>
                                    <td>
                                        {{$report->flag?__('dashboard.Done'):__('dashboard.Pending')}}
                                        -
                                        {{$report->status?__('dashboard.True'):__('dashboard.Cancelled')}}
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <form action="{{route('admin-member-cash-withdraw-edit',$report->id)}}" method="post">
                            {{csrf_field()}}
                            <div class="col-md-6">
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{__('dashboard.Change Withdraw Date')}}</label>
                                    <input type="text" class="form-control datepicker" name="withdraw"
                                           value="{{\Carbon\Carbon::parse($report->withdraw_date)->format('Y-m-d H:i')}}">
                                </div>
                                <button class="btn blue">{{__('dashboard.Submit')}}</button>
                            </div>
                        </form>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END CONTAINER -->
@endsection

@section('scripts')
    <script>
        $('input[name="withdraw"].datepicker').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            timePicker: true,
            locale: {
                format: 'YYYY-MM-DD hh:mm',
            },
        });
    </script>
@endsection
