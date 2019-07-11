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
                                <h1>{{__('dashboard.Holidays')}}</h1>
                            </div>
                        </div>
                    </div>
                    <div class="page-content">
                        <div class="container">
                            <div class="portlet light">
                                {{--<div id="calendar-view"></div>--}}
                                {!!  $calendar_view->calendar()!!}
                            </div>
                        </div>
                    </div>


                </div>


            </div>
        </div>


    </div>
    <!-- END CONTAINER -->

@endsection

@section('scripts')
    <script src="{{asset('backend/plugin/calendar/dist/fullcalendar.min.js')}}"></script>
    {!! $calendar_view->script() !!}
    <script src="{{asset('backend/js/admin/calendar.js')}}"></script>
@endsection

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('backend/plugin/calendar/dist/fullcalendar.min.css')}}">
    <link rel="stylesheet" href="{{asset('backend/plugin/calendar/dist/fullcalendar.print.min.css')}}"
          media="print">
    <style>
        .fc-event{
            min-height:30px;
            padding:3px;
        }
    </style>
@endsection