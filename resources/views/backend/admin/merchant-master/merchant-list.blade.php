@extends('backend.layouts.master')

@section('content')
    <div class="page-wrapper-row full-height">
        <div class="page-wrapper-middle">
            <div class="page-container">
                <div class="container">
                    @include('fragments.message')

                    <div class="row">
                        <div class="col-md-12">

                            <div class="portlet light ">
                                <div class="portlet-title">
                                    <div class="caption font-dark">
                                        <i class="icon-users font-dark"></i>
                                        <span class="caption-subject bold uppercase">{{__('dashboard.Merchants')}}</span>
                                    </div>
                                    <div class="tools"></div>
                                </div>
                                <div class="portlet-body">
                                    <table class="table table-striped table-bordered table-hover dataTable dtr-inline" id="sample_1">
                                        <thead>
                                        <tr>
                                            <th style="width: 15%">{{__('dashboard.Name')}}</th>
                                            <th style="width: 10%">
                                                {{__('dashboard.Identification')}}
                                            </th>
                                            <th style="width: 15%">{{__('dashboard.Login Id')}}
                                            </th>
                                            <th style="width: 15%">{{__('dashboard.Business Name')}}
                                            </th>

                                            <th style="width: 10%">{{__('dashboard.Joined Date')}}
                                            </th>
                                            <th style="width: 15%"> {{__('dashboard.Action')}}</th>
                                            <th style="width: 10%">{{__('dashboard.Status')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($merchants as $key=>$merchant)
                                            <tr class="{{($key%2==0)?'even':'odd'}}">
                                                <td>{{$merchant->name}} {{$merchant->surname}}</td>
                                                <td>{{$merchant->identification_type}}
                                                    <br>{{$merchant->identification_number}}</td>
                                                <td>{{$merchant->user_name}}</td>
                                                <td>{{$merchant->getBusiness->name??''}}</td>
                                                <td>{{$merchant->joining_date}}</td>
                                                <td>
                                                    <a title="detail"
                                                       href="{{route('merchant-product-id',$merchant->id)}}"><i
                                                                class="fa fa-edit"> {{__('dashboard.Detail')}}</i></a>
                                                   </td>
                                                <td>
                                                    @if($merchant->status ===1)
                                                        <a title="click to disable"
                                                           href="{{route('change-status-merchant-admin',$merchant->id)}}"><i
                                                                    class="fa fa-certificate text-success"><i
                                                                        class="fa fa-check"></i></i></a>
                                                    @else
                                                        <a title="click to enable"
                                                           href="{{route('change-status-merchant-admin',$merchant->id)}}"><i
                                                                    class="fa fa-certificate text-danger"><i
                                                                        class="fa fa-times"></i></i></a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8"></td>
                                            </tr>
                                        @endforelse

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