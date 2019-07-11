@extends('backend.layouts.master')

@section('content')
    <body class="page-container-bg-solid">
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
                                <h1>{{__('dashboard.Notifications')}}
                                    <small></small>
                                </h1>
                            </div>
                            <!-- END PAGE TITLE -->
                            <!-- BEGIN PAGE TOOLBAR -->

                        </div>
                    </div>
                    <!-- END PAGE HEAD-->
                    <!-- BEGIN PAGE CONTENT BODY -->
                    <div class="page-content">
                        <div class="container">
                            @include('fragments.message')
                            <table class="table table-borderless">
                                <?php $group_date = [];?>
                                @forelse($notices as $notice)
                                    @if(!in_array($notice->group_date,$group_date))
                                        <tr style="background: white">
                                            <th colspan="2">{{Carbon\Carbon::parse($notice->group_date)->format('l, F d, Y')}}</th>
                                        </tr>
                                        <?php $group_date[] = $notice->group_date?>
                                    @endif
                                    <tr>
                                        <td>{{$notice->desc}}</td>
                                        <td>{{Carbon\Carbon::parse($notice->created_at)->diffForHumans()}}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2">
                                            No notifications available.
                                        </td>
                                    </tr>
                                @endforelse
                            </table>
                            {{$notices->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop