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
                                        <span class="caption-subject bold uppercase"> {{__('dashboard.Subscribers')}}</span>
                                    </div>

                                </div>
                                <div class="portlet-body">

                                    <table class="table table-striped table-bordered table-hover dataTable dtr-inline"
                                           id="sample_2">
                                        <thead>
                                        <tr>
                                            <th>{{__('dashboard.SN')}}</th>
                                            <th>{{__('dashboard.Subscriber Email')}}</th>
                                            <th>{{__('dashboard.Type')}}</th>
                                            <th>{{__('dashboard.Status')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @foreach($lists as $key=>$item)
                                            <tr>
                                                <td>{{++$key}}</td>
                                                <td>{{$item->email}}</td>
                                                <td>{{$item->type}}</td>
                                                <td>
                                                    <form action="{{route('admin-subscribe-status',$item->id)}}"
                                                          method="post"
                                                          style="display:inline-block">
                                                        {{csrf_field()}}
                                                        @if($item->status ===1)
                                                            <button class="btn btn-sm green"
                                                                    style="width:50px"
                                                                    type="submit"><i class="fa fa-check"></i>
                                                            </button>
                                                        @else
                                                            <button class="btn btn-sm red"
                                                                    style="width:50px"
                                                                    type="submit"><i class="fa fa-times"></i>
                                                            </button>

                                                        @endif
                                                    </form>
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
        .dataTables_wrapper .dataTables_filter {
            display: block !important;
        }
    </style>
@stop