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
                                        <span class="caption-subject bold uppercase"> {{__('dashboard.About Page')}}</span>
                                    </div>

                                    <div class="pull-right">
                                        <a class="btn btn-success"
                                           href="{{route('admin-add-about-content')}}"> {{__('dashboard.Add Contents')}}</a>
                                    </div>

                                </div>
                                <div class="portlet-body">


                                    <table class="table table-striped">
                                        <tr>
                                            <th>#</th>
                                            <th>{{__('dashboard.Title')}}</th>
                                            <th>{{__('dashboard.Image')}}</th>
                                            <th>{{__('dashboard.Description')}}</th>
                                        </tr>
                                        <?php $page = $_GET['page'] ?? '1';?>

                                        @forelse($abouts as $key=>$about)
                                            <tr>
                                                <td>{{++$key + (25*($page-1))}}</td>
                                                <td>{{$about->title}}</td>
                                                <td> @if(!empty($about->image))
                                                        <img src="{{asset('image/about/'.$about->image)}}" alt="image"
                                                             height="60px">
                                                    @else
                                                        No image available.
                                                    @endif
                                                </td>
                                                <td><p>{!!$about->description!!}</p></td>
                                                <td><a href="{{route('admin-edit-about-content',$about->id)}}"><i
                                                                class="fa fa-edit"></i></a>
                                                </td>
                                                <td>
                                                    <a href="{{route('admin-destroy-about-content',$about->id)}}"><i
                                                                class="fa fa-trash-o"></i></a>
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
        p {
            word-break: break-all;
        }

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