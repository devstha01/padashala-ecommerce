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
                                        <i class="fa fa-file-image-o font-dark"></i>
                                        <span class="caption-subject bold uppercase">{{__('dashboard.Banners')}}</span>
                                    </div>
                                    <div class="pull-right">
                                        <a class="btn btn-success"
                                           href="{{route('admin-add-banner')}}">{{__('dashboard.Add Banner')}}</a>
                                    </div>
                                </div>
                                <div class="portlet-body">


                                    <table class="table table-striped">
                                        <tr>
                                            <th>#</th>
                                            <th>{{__('dashboard.Image')}}</th>
                                            <th>{{__('dashboard.Website Url')}}</th>
                                            <th>{{__('dashboard.Type')}}</th>
                                            <th>{{__('dashboard.Product Link')}}</th>
                                            <th>{{__('dashboard.Status')}}</th>
                                            <th colspan="2">{{__('dashboard.Action')}}</th>

                                        </tr>
                                        <?php $page = $_GET['page'] ?? '1';?>

                                        @forelse($homebanners as $key=>$homebanner)
                                            <tr>
                                                <td>{{++$key}}</td>
                                                <td> @if(!empty($homebanner->image))
                                                        <img src="{{asset('image/homebanner/'.$homebanner->image)}}"
                                                             alt="image"
                                                             height="60px">
                                                    @else
                                                        {{__('dashboard.No image available')}}.
                                                    @endif
                                                </td>
                                                <td>{{$homebanner->url}}</td>
                                                <td>{{$homebanner->type}}</td>
                                                <td>
                                                    {{$homebanner->slug}}
                                                </td>
                                                <td>
                                                    @if($homebanner->status)
                                                        <a href="{{route('admin-status-banner',$homebanner->id)}}">
                                                            <button class="btn green fa fa-check-circle"> {{__('dashboard.Disable')}}</button>
                                                        </a>
                                                    @else
                                                        <a href="{{route('admin-status-banner',$homebanner->id)}}">
                                                            <button class="btn red fa fa-times-circle"> {{__('dashboard.Enable')}}</button>
                                                        </a>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{route('admin-edit-banner',$homebanner->id)}}">
                                                        <button class="btn blue fa fa-edit"> {{__('dashboard.Edit')}}</button>
                                                    </a>
                                                </td>

                                                <td>
                                                    <form action="{{route('admin-destroy-banner',$homebanner->id)}}"
                                                          method="post">
                                                        {{csrf_field()}}
                                                        <button onclick="return confirm('Are you sure?')"
                                                                type="submit"
                                                                class="btn fa fa-trash blue"> {{__('dashboard.Delete')}}
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5"></td>
                                            </tr>
                                        @endforelse
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
    </style>
@stop

@section('scripts')
    <script src="{{asset('backend/js/admin/search-merchant.js')}}" type="text/javascript"></script>
@stop