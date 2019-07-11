@extends('backend.layouts.master')

@section('content')
    <div class="page-wrapper-row full-height">
        <div class="page-wrapper-middle">
            <div class="page-container">
                <div class="container">
                    @include('fragments.message')

                    <div class="row">
                        <div class="col-md-12">
                            <!-- BEGIN EXAMPLE TABLE PORTLET-->
                            <div class="portlet light ">
                                <div class="portlet-title">
                                    <div class="caption font-dark">
                                        <i class="icon-users font-dark"></i>
                                        <span class="caption-subject bold uppercase"> {{__('dashboard.Staffs')}}</span>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <table class="table table-striped table-bordered table-hover dataTable dtr-inline" id="sample_2">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{__('dashboard.Name')}}</th>
                                            <th>{{__('dashboard.Login Name')}}</th>
                                            <th>{{__('dashboard.Role')}}</th>
                                            <th>{{__('dashboard.Position')}}</th>
                                            <th>{{__('dashboard.Joined Date')}}</th>
                                            <th colspan="2">{{__('dashboard.Action')}}</th>
                                            <th>{{__('dashboard.Status')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($admins as $key=>$staff)
                                            <tr>
                                                <td>{{++$key }}</td>
                                                <td>{{$staff->name}} {{$staff->surname}}</td>
                                                <td>{{$staff->user_name}}</td>
                                                <td>{{strtoupper($staff->role)}}</td>
                                                <td>{{$staff->position}}</td>
                                                <td>{{$staff->joining_date}}</td>

                                                <td>
                                                    <a href="{{route('edit-staff-id',$staff->id)}}"><i
                                                                class="fa fa-edit btn blue"> {{__('dashboard.Edit')}}</i></a>
                                                </td>
                                                <td>
                                                    <a href="{{route('edit-staff-permission',$staff->id)}}"><i
                                                                class="fa fa-key btn btn-primary"> {{__('dashboard.Permission')}}</i></a>
                                                </td>

                                                <td>
                                                    @if($staff->status ===1)
                                                        <a title="click to disable"
                                                           href="{{route('change-status-staff-admin',$staff->id)}}"><i
                                                                    class="fa fa-check-circle text-success"></i> {{__('dashboard.Disable')}}
                                                        </a>
                                                    @else
                                                        <a title="click to enable"
                                                           href="{{route('change-status-staff-admin',$staff->id)}}"><i
                                                                    class="fa fa-times-circle text-danger"></i>
                                                            {{__('dashboard.Enable')}}</a>
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
    </div>
@stop


@section('stylesheets')
    <style>
        #search-merchant-list {
            position: absolute;
            background: white;
            width: 200px;
            max-height: 200px;
            overflow-y: scroll;
        }

        #search-merchant-list a {
            margin-top: 10px;
        }

        .dataTables_wrapper .dataTables_filter {
            display: block !important;
        }
    </style>
@stop

@section('scripts')
    <script src="{{asset('backend/js/admin/search-merchant.js')}}" type="text/javascript"></script>
@stop