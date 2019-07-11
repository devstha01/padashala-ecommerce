@extends('backend.layouts.master')

@section('content')

    <style>
        .table.overview tr {
            border: 0;
        }

        .table.overview tr td {
            vertical-align: middle;
            border: 0;
        }

        .table.overview {
            margin-bottom: 0;
        }

        .table.overview > tbody > tr > td, .table.overview > tbody > tr > th, .table.overview > tfoot > tr > td, .table.overview > tfoot > tr > th, .table.overview > thead > tr > td, .table.overview > thead > tr > th {
            padding: 0;
        }


    </style>

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
                                <h1>{{$success_title??''}}</h1>
                            </div>
                        </div>
                    </div>
                    <div class="page-content">
                        <div class="container">
                            {{--@include('backend.member.wallet-card')--}}
                            <div class="page-body">
                                <div class="alert alert-success alert-dismissible">
                                    <h4><i class="icon fa fa-check"></i> {{$success_brief??''}}. {{$success_detail??''}}</h4>
                                </div>
                                {{--<div class="portlet light">--}}
                                    {{--<h4>{{$success_detail??''}}</h4>--}}
                                {{--</div>--}}
                            </div>

                        </div>
                    </div>

                </div>
            </div>


        </div>
        <!-- END CONTAINER -->
    </div>
@stop
