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
                                        <span class="caption-subject bold uppercase">{{__('dashboard.Blog Page')}}</span>
                                    </div>

                                    <div class="pull-right">
                                        <a class="btn btn-success"
                                           href="{{route('admin-add-blog-content')}}"> {{__('dashboard.Add Blog Contents')}}</a>
                                    </div>

                                </div>
                                <div class="portlet-body">


                                    <table class="table table-striped">
                                        <tr>
                                            <th>#</th>
                                            <th>{{__('dashboard.Title')}}</th>
                                            <th>{{__('dashboard.Image')}}</th>
                                            <th>{{__('dashboard.Description')}}</th>
                                            <th>{{__('dashboard.Publication Date')}}</th>
                                            <th>{{__('dashboard.Author')}}</th>
                                            <th>{{__('dashboard.Action')}}</th>
                                        </tr>
                                        <?php $page = $_GET['page'] ?? '1';?>

                                        @forelse($blogs as $key=>$blog)
                                            <tr>
                                                <td>{{++$key + (25*($page-1))}}</td>
                                                <td>{{$blog->title}}</td>
                                                <td> @if(!empty($blog->image))
                                                        <img src="{{asset('image/blog/'.$blog->image)}}" alt="image"
                                                             height="60px">
                                                    @else
                                                        {{__('dashboard.No image available')}}.
                                                    @endif
                                                </td>
                                                <td><p>{!!$blog->description!!}</p></td>
                                                <td><p>{{$blog->date_published}}</p></td>
                                                <td><p>{{$blog->author}}</p></td>


                                                <td><a href="{{route('admin-edit-blog-content',$blog->id)}}"><i
                                                                class="fa fa-edit"></i></a>
                                                </td>
                                                <td>
                                                    <a href="{{route('admin-destroy-blog-content',$blog->id)}}"><i
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