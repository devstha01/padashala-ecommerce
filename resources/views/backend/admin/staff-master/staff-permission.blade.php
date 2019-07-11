@extends('backend.layouts.master')
@section('stylesheets')
    <link href="{{asset('backend/assets/pages/css/profile.min.css')}}" rel="stylesheet" type="text/css"/>
@endsection

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
                                <h1>
                                    {{strtoupper($staff->role)}} | {{__('dashboard.Permission')}}
                                    <br>
                                    <small>
                                        {{$staff->name}} {{$staff->surname}} [ {{$staff->user_name}} ]
                                    </small>
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

                        <!-- BEGIN PAGE CONTENT INNER -->
                            <div class="page-content-inner">
                                <div class="row">
                                    <div class="col-md-12">
                                        <!-- BEGIN PROFILE SIDEBAR -->
                                        <form action="{{route('change-permission',$staff->id)}}" method="post">
                                            {{csrf_field()}}
                                            <table class="table table-hover table-bordered">
                                                @if($staff->role ==='admin')
                                                    <tr>
                                                        <td colspan="3" class="text-center">
                                                            Admin have full permission
                                                        </td>
                                                    </tr>
                                                @endif

                                                <tr>
                                                    <th>{{__('dashboard.Permission')}}</th>
                                                    <th>{{__('dashboard.View')}}</th>
                                                    <th>{{__('dashboard.Edit')}}</th>
                                                </tr>
                                                @foreach($permissions as $master=>$permission)
                                                    <tr>
                                                        <th colspan="3" style="color:#0088cc">{{$master}}</th>
                                                    </tr>
                                                    @foreach($permission as $item)
                                                        <tr>
                                                            <td>
                                                                {{$item['name']}}
                                                            </td>
                                                            <td>
                                                                @if(in_array('1.'.$item['master'].'.'.$item['name'],$available_permissions))
                                                                    @if($staff->role ==='admin')
                                                                 <i class="fa fa-check-square"></i>
                                                                    @else
                                                                    <input type="checkbox" name="permission[]"
                                                                               class="view-permission"
                                                                               value="{{'1.'.$item['master'].'.'.$item['name']}}"
                                                                                {{(in_array('1.'.$item['master'].'.'.$item['name'],$access))?'checked':''}}>
                                                                    @endif
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if(in_array('2.'.$item['master'].'.'.$item['name'],$available_permissions))
                                                                    @if($staff->role ==='admin')
                                                                        <i class="fa fa-check-square"></i>
                                                                    @else
                                                                    <input type="checkbox" name="permission[]"
                                                                               class="edit-permission"
                                                                               value="{{'2.'.$item['master'].'.'.$item['name']}}"
                                                                                {{(in_array('2.'.$item['master'].'.'.$item['name'],$access))?'checked':''}}>
                                                                    @endif
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endforeach
                                                <tr>
                                                    <td colspan="3">
                                                        @if(!$staff->hasRole('Admin'))
                                                            <button type="submit"
                                                                    class="btn blue form-control">{{__('dashboard.Update')}}</button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            </table>
                                            <br>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- END PAGE CONTENT INNER -->
                        </div>
                    </div>
                    <!-- END PAGE CONTENT BODY -->
                    <!-- END CONTENT BODY -->
                </div>
            </div>
        </div>
    </div>
    <!-- END CONTENT -->
    @stop

    @section('scripts')
        <script>
            $(function () {
                $('.view-permission').on('change', function () {
                    var name = $(this).val();
                    name = name.replace("1", "2");
                    if (!$(this).is(":checked")) {
                        $('.edit-permission[value="' + name + '"]').prop('checked', false);
                    }
                });

                $('.edit-permission').on('change', function () {
                    var name = $(this).val();
                    name = name.replace("2", "1");
                    if ($(this).is(":checked")) {
                        $('.view-permission[value="' + name + '"]').prop('checked', true);
                    }
                });

            });
        </script>
@endsection