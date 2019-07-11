@extends('backend.layouts.master')

@section('content')
    <div class="page-wrapper-row full-height">
        <div class="page-wrapper-middle">
            <div class="page-container">
                <div class="container">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="portlet light">

                                <div class="portlet-title">
                                    <h3> {{Lang::get('dashboard.Categories')}}
                                        <a class="add-category add-modal" data-toggle="modal" data-target="#addModal"
                                           data-type="category" data-obj=""><i
                                                    class=" fa fa-plus"></i> {{Lang::get('dashboard.Add Category')}}</a>
                                    </h3>
                                </div>
                                <div class="portlet-body">
                                    @include('fragments.message')
                                    <ul class="category-ul">
                                        @forelse($categories as $category)

                                            @if(count($category->getSubCategory) !== 0)
                                                <li>
                                    <span class="has-sub"> <i
                                                class="fa fa-chevron-right"></i> {{$category->name}}</span>
                                                    @if(!empty($category->image))
                                                        <img src="{{asset('image/admin/category/'.$category->image)}}"
                                                             alt="image" height="30px">
                                                    @endif
                                                    <a class="action-button" onclick="return confirm('are you sure ?')"
                                                       href="{{route('delete-category-e-commerce-admin',$category->id)}}"><i
                                                                class="fa fa-trash text-danger"></i></a>
                                                    @if($category->status === 1)
                                                        <a class="action-button" onclick="return confirm('Are you sure?')"
                                                           href="{{route('status-category-admin',['type'=>'category','id'=>$category->id])}}"
                                                           title="click to disable"><i
                                                                    class="fa fa-certificate text-success"></i></a>
                                                    @else
                                                        <a class="action-button" onclick="return confirm('Are you sure?')"
                                                           href="{{route('status-category-admin',['type'=>'category','id'=>$category->id])}}"
                                                           title="click to enable"><i
                                                                    class="fa fa-certificate text-danger"></i></a>
                                                    @endif
                                                    <a class="action-button edit-modal" data-toggle="modal"
                                                       data-target="#editModal"
                                                       data-type="category" data-obj="{{$category}}"><i
                                                                class="fa fa-edit text-info"></i></a>
                                                    <a class="action-button add-modal" title="add sub-category"
                                                       data-toggle="modal" data-target="#addModal"
                                                       data-type="sub-category" data-obj="{{$category}}"><i
                                                                class=" fa fa-plus"></i></a>
                                                    <ul class="to-sub {{session('cat') == $category->id?'active':''}}">

                                                        @forelse($category->getSubCategory as $subCategory)

                                                            @if(count($subCategory->getSubChildCategory) !== 0)
                                                                <li>
                                                            <span class="has-sub"> <i
                                                                        class="fa fa-angle-right"></i> {{$subCategory->name}}</span>
                                                                    @if(!empty($subCategory->image))
                                                                        <img src="{{asset('image/admin/category/'.$subCategory->image)}}"
                                                                             alt="image" height="30px">
                                                                    @endif
                                                                    <a class="action-button"
                                                                       onclick="return confirm('are you sure ?')"
                                                                       href="{{route('delete-sub-category-e-commerce-admin',$subCategory->id)}}"><i
                                                                                class="fa fa-trash text-danger"></i></a>

                                                                    @if($subCategory->status === 1)
                                                                        <a class="action-button" onclick="return confirm('Are you sure?')"
                                                                           href="{{route('status-category-admin',['type'=>'sub-category','id'=>$subCategory->id])}}"
                                                                           title="click to disable"><i
                                                                                    class="fa fa-certificate text-success"></i></a>
                                                                    @else
                                                                        <a class="action-button" onclick="return confirm('Are you sure?')"
                                                                           href="{{route('status-category-admin',['type'=>'sub-category','id'=>$subCategory->id])}}"
                                                                           title="click to enable"><i
                                                                                    class="fa fa-certificate text-danger"></i></a>
                                                                    @endif

                                                                    <a class="action-button edit-modal"
                                                                       data-toggle="modal"
                                                                       data-target="#editModal"
                                                                       data-type="sub-category"
                                                                       data-obj="{{$subCategory}}"><i
                                                                                class="fa fa-edit text-info"></i></a>
                                                                    <a class="action-button add-modal"
                                                                       title="add sub-child-category"
                                                                       data-toggle="modal"
                                                                       data-target="#addModal"
                                                                       data-type="sub-child-category"
                                                                       data-obj="{{$subCategory}}"><i
                                                                                class="fa fa-plus"></i></a>
                                                                    <ul class="to-sub {{session('sub') == $subCategory->id?'active':''}}">

                                                                        @forelse($subCategory->getSubChildCategory as $subChildCategory)
                                                                            <li>
                                                                        <span>
                                                                            <i class="fa fa-asterisk small-text"></i>
                                                                            {{$subChildCategory->name}}</span>
                                                                                @if(!empty($subChildCategory->image))
                                                                                    <img src="{{asset('image/admin/category/'.$subChildCategory->image)}}"
                                                                                         alt="image" height="30px">
                                                                                @endif
                                                                                <a class="action-button"
                                                                                   onclick="return confirm('are you sure ?')"
                                                                                   href="{{route('delete-sub-child-category-e-commerce-admin',$subChildCategory->id)}}"><i
                                                                                            class="fa fa-trash text-danger"></i></a>

                                                                                @if($subChildCategory->status === 1)
                                                                                    <a class="action-button" onclick="return confirm('Are you sure?')"
                                                                                       href="{{route('status-category-admin',['type'=>'sub-child-category','id'=>$subChildCategory->id])}}"
                                                                                       title="click to disable"><i
                                                                                                class="fa fa-certificate text-success"></i></a>
                                                                                @else
                                                                                    <a class="action-button" onclick="return confirm('Are you sure?')"
                                                                                       href="{{route('status-category-admin',['type'=>'sub-child-category','id'=>$subChildCategory->id])}}"
                                                                                       title="click to enable"><i
                                                                                                class="fa fa-certificate text-danger"></i></a>
                                                                                @endif

                                                                                <a class="action-button edit-modal"
                                                                                   data-toggle="modal"
                                                                                   data-target="#editModal"
                                                                                   data-type="sub-child-category"
                                                                                   data-obj="{{$subChildCategory}}"><i
                                                                                            class="fa fa-edit text-info"></i></a>
                                                                            </li>
                                                                        @empty
                                                                        @endforelse
                                                                    </ul>
                                                                </li>
                                                            @else
                                                                <li>
                                                            <span>
                                                                 <i class="fa fa-asterisk small-text"></i>
                                                                {{$subCategory->name}}</span>
                                                                    @if(!empty($subCategory->image))
                                                                        <img src="{{asset('image/admin/category/'.$subCategory->image)}}"
                                                                             alt="image" height="30px">
                                                                    @endif
                                                                    <a class="action-button"
                                                                       onclick="return confirm('are you sure ?')"
                                                                       href="{{route('delete-sub-category-e-commerce-admin',$subCategory->id)}}"><i
                                                                                class="fa fa-trash text-danger"></i></a>

                                                                    @if($subCategory->status === 1)
                                                                        <a class="action-button" onclick="return confirm('Are you sure?')"
                                                                           href="{{route('status-category-admin',['type'=>'sub-category','id'=>$subCategory->id])}}"
                                                                           title="click to disable"><i
                                                                                    class="fa fa-certificate text-success"></i></a>
                                                                    @else
                                                                        <a class="action-button" onclick="return confirm('Are you sure?')"
                                                                           href="{{route('status-category-admin',['type'=>'sub-category','id'=>$subCategory->id])}}"
                                                                           title="click to enable"><i
                                                                                    class="fa fa-certificate text-danger"></i></a>
                                                                    @endif

                                                                    <a class="action-button edit-modal"
                                                                       data-toggle="modal"
                                                                       data-target="#editModal"
                                                                       data-type="sub-category"
                                                                       data-obj="{{$subCategory}}"><i
                                                                                class="fa fa-edit text-info"></i></a>
                                                                    <a class="action-button add-modal"
                                                                       title="add sub-child-category"
                                                                       data-toggle="modal"
                                                                       data-target="#addModal"
                                                                       data-type="sub-child-category"
                                                                       data-obj="{{$subCategory}}"><i
                                                                                class="fa fa-plus"></i></a>
                                                                </li>
                                                            @endif
                                                        @empty
                                                        @endforelse
                                                    </ul>
                                                </li>
                                            @else
                                                <li>
                                            <span>
                                                <i class="fa fa-asterisk small-text"></i>
                                                {{$category->name}}</span>
                                                    @if(!empty($category->image))
                                                        <img src="{{asset('image/admin/category/'.$category->image)}}"
                                                             alt="image" height="30px">
                                                    @endif
                                                    <a class="action-button" onclick="return confirm('are you sure ?')"
                                                       href="{{route('delete-category-e-commerce-admin',$category->id)}}"><i
                                                                class="fa fa-trash text-danger"></i></a>

                                                    @if($category->status === 1)
                                                        <a class="action-button" onclick="return confirm('Are you sure?')"
                                                           href="{{route('status-category-admin',['type'=>'category','id'=>$category->id])}}"
                                                           title="click to disable"><i
                                                                    class="fa fa-certificate text-success"></i></a>
                                                    @else
                                                        <a class="action-button" onclick="return confirm('Are you sure?')"
                                                           href="{{route('status-category-admin',['type'=>'category','id'=>$category->id])}}"
                                                           title="click to enable"><i
                                                                    class="fa fa-certificate text-danger"></i></a>
                                                    @endif

                                                    <a class="action-button edit-modal" data-toggle="modal"
                                                       data-target="#editModal"
                                                       data-type="category" data-obj="{{$category}}"><i
                                                                class="fa fa-edit text-info"></i></a>
                                                    <a class="action-button  add-modal" title="add sub-category"
                                                       data-toggle="modal" data-target="#addModal"
                                                       data-type="sub-category" data-obj="{{$category}}"><i
                                                                class=" fa fa-plus"></i></a>
                                                </li>
                                            @endif
                                        @empty
                                            <li>No categories added</li>
                                        @endforelse
                                    </ul>


                                    <!-- Add Modal -->
                                    <div class="modal fade" id="addModal" tabindex="-1"
                                         role="dialog" aria-labelledby="editModalLabel"
                                         aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Add <span id="modal-type"></span> to <b
                                                                id="modal-name"></b></h4>
                                                    <button type="button" class="close"
                                                            data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{route('add-sub-category-e-commerce-admin')}}"
                                                          method="post"
                                                          enctype="multipart/form-data">
                                                        {{csrf_field()}}
                                                        <input type="text" id="modal-id" name="id"
                                                               style="display: none">
                                                        <input type="text" id="modal-type-1" name="type"
                                                               style="display: none">
                                                        <label>
                                                            Name <span class="m-l-5 text-danger">*</span>
                                                        </label>
                                                        <input type="text" name="name" class="form-control input-sm">
                                                        <br>
                                                        <label>
                                                            Image
                                                        </label>
                                                        <input type="file" name="image">
                                                        <br>
                                                        <button type="submit" class="btn btn-success">Submit</button>
                                                    </form>
                                                </div>
                                                <br>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Close
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editModal" tabindex="-1"
                                         role="dialog" aria-labelledby="editModalLabel"
                                         aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Edit <span id="edit-modal-type"></span></h4>
                                                    <button type="button" class="close"
                                                            data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{route('edit-category-e-commerce-admin')}}"
                                                          method="post"
                                                          enctype="multipart/form-data">
                                                        {{csrf_field()}}
                                                        <input type="text" id="edit-modal-id" name="id"
                                                               style="display: none">
                                                        <input type="text" id="edit-modal-type-1" name="type"
                                                               style="display: none">
                                                        <label>
                                                            Name <span class="m-l-5 text-danger">*</span>
                                                        </label>
                                                        <input type="text" name="name" id="edit-modal-name"
                                                               class="form-control input-sm">
                                                        <br>
                                                        <label>
                                                            Image
                                                        </label>
                                                        <input type="file" name="image">
                                                        <br>
                                                        <div id="edit-modal-image">
                                                        </div>
                                                        <br>
                                                        <button type="submit" class="btn btn-success">Submit</button>
                                                    </form>
                                                </div>
                                                <br>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Close
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
        .category-ul li {
            /*width: 200px;*/
            /*height: 25px;*/
            list-style: none;
            margin: 10px 0;
            padding-top: 5px;
            border-top: 1px solid whitesmoke;
        }

        .category-ul span {
            font-size: 16px;
            cursor: pointer;
        }

        .action-button {
            float: right;
            font-size: 16px;
            margin-left: 20px;
        }

        .to-sub {
            display: none;
        }

        .category-active{
            display: block;
        }

        .add-category {
            font-size: 16px;
            float: right;
        }

        .small-text {
            font-size: 8px;
        }
    </style>
@stop
@section('scripts')
    <script src="{{ URL::asset('backend/js/admin/category-admin-1.js') }}" type="text/javascript"></script>
@stop