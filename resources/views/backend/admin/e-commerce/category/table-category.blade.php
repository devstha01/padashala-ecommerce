@extends('backend.layouts.master')

@section('content')
    <div class="page-wrapper-row full-height">
        <div class="page-wrapper-middle">
            <div class="page-container">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="portlet light">

                                <div class="portlet-title">
                                    <h3> {{__('dashboard.Categories')}}
                                        <a class="add-category add-modal" data-toggle="modal" data-target="#addModal"
                                           data-type="category" data-obj=""><i
                                                    class=" fa fa-plus"></i> {{__('dashboard.Add Category')}}</a>
                                    </h3>
                                </div>
                                <div class="portlet-body">
                                    @include('fragments.message')
                                    <span class="hide" id="toggle-active" data-category="{{session('cat')??0}}"
                                          data-subcategory="{{session('sub')??0}}"></span>
                                    <table class="table table-hover">
                                        <tr>
                                            <th></th>
                                            <th>{{__('dashboard.Category')}} [{{__('dashboard.Share')}}%]</th>
                                            <th>{{__('dashboard.Sub-Category')}}</th>
                                            <th>{{__('dashboard.Sub-Child-Category')}}</th>
                                            <th>{{__('dashboard.Image')}}</th>
                                            <th>{{__('dashboard.Action')}}</th>
                                        </tr>
                                        @forelse($categories as $category)
                                            <tr>
                                                <td>
                                                    @if(count($category->getSubCategory) !==0)
                                                        <i class="fa fa-plus has-sub trigger-category-{{$category->id}}"
                                                           data-subclass="category-{{$category->id}}"></i>
                                                    @endif
                                                </td>
                                                <td colspan="3">{{$category->name}} [{{floatval($category->share_percentage)}}%]</td>
                                                <td>
                                                    @if($category->image!==null)
                                                        <img src="{{asset('image/admin/category/'.$category->image)}}"
                                                             alt="image" height="30px">
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($category->status === 1)
                                                        <a class="action-button"
                                                           onclick="return confirm('Disable this category?')"
                                                           href="{{route('status-category-admin',['type'=>'category','id'=>$category->id])}}"
                                                           title="click to disable"><i
                                                                    class="fa fa-certificate text-success"> <i
                                                                        class="fa fa-check"></i></i></a>
                                                    @else
                                                        <a class="action-button"
                                                           onclick="return confirm('Enable this category?')"
                                                           href="{{route('status-category-admin',['type'=>'category','id'=>$category->id])}}"
                                                           title="click to enable"><i
                                                                    class="fa fa-certificate text-danger"> <i
                                                                        class="fa fa-times"></i></i></a>
                                                    @endif
                                                    <a class="action-button edit-modal" data-toggle="modal"
                                                       data-target="#editModal"
                                                       data-type="category" data-obj="{{$category}}"><i
                                                                class="fa fa-edit text-info"> {{__('dashboard.Edit')}}</i></a>
                                                    <a class="action-button add-modal" title="add sub-category"
                                                       data-toggle="modal" data-target="#addModal"
                                                       data-type="sub-category" data-obj="{{$category}}"><i
                                                                class=" fa fa-plus"> {{__('dashboard.sub category')}}</i></a>
                                                </td>
                                            </tr>

                                            @forelse($category->getSubCategory as $subCategory)
                                                <tr class="to-sub hide category-{{$category->id}}">
                                                    <td>
                                                        @if(count($subCategory->getSubChildCategory) !==0)
                                                            <i class="fa fa-plus has-sub trigger-subcategory-{{$subCategory->id}} plus-category-{{$category->id}}"
                                                               data-subclass="subcategory-{{$subCategory->id}}"></i>
                                                        @endif
                                                    </td>
                                                    <td class="text-right">
                                                        <i class="fa fa-level-up fa-rotate-90"></i>
                                                    </td>
                                                    <td colspan="2">{{$subCategory->name}}</td>
                                                    <td>
                                                        @if($subCategory->image !==null)
                                                            <img src="{{asset('image/admin/category/'.$subCategory->image)}}"
                                                                 alt="image" height="30px">
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($subCategory->status === 1)
                                                            <a class="action-button"
                                                               onclick="return confirm('Disable this sub categroy?')"
                                                               href="{{route('status-category-admin',['type'=>'sub-category','id'=>$subCategory->id])}}"
                                                               title="click to disable"><i
                                                                        class="fa fa-certificate text-success"> <i
                                                                            class="fa fa-check"></i></i></a>
                                                        @else
                                                            <a class="action-button"
                                                               onclick="return confirm('Enable this sub category?')"
                                                               href="{{route('status-category-admin',['type'=>'sub-category','id'=>$subCategory->id])}}"
                                                               title="click to enable"><i
                                                                        class="fa fa-certificate text-danger"> <i
                                                                            class="fa fa-times"></i></i></a>
                                                        @endif

                                                        <a class="action-button edit-modal"
                                                           data-toggle="modal"
                                                           data-target="#editModal"
                                                           data-type="sub-category"
                                                           data-obj="{{$subCategory}}"><i
                                                                    class="fa fa-edit text-info"> {{__('dashboard.Edit')}}</i></a>
                                                        <a class="action-button add-modal"
                                                           title="add sub-child-category"
                                                           data-toggle="modal"
                                                           data-target="#addModal"
                                                           data-type="sub-child-category"
                                                           data-obj="{{$subCategory}}"><i
                                                                    class="fa fa-plus"> {{__('dashboard.sub child category')}}</i></a>
                                                    </td>
                                                </tr>


                                                @forelse($subCategory->getSubChildCategory as $subChildCategory)
                                                    <tr class="to-sub hide minus-category-{{$category->id}} subcategory-{{$subCategory->id}}">
                                                        <td colspan="2"></td>
                                                        <td class="text-right">
                                                            <i class="fa fa-level-up fa-rotate-90"></i>
                                                        </td>
                                                        <td>{{$subChildCategory->name}}</td>
                                                        <td>
                                                            @if(!empty($subChildCategory->image))
                                                                <img src="{{asset('image/admin/category/'.$subChildCategory->image)}}"
                                                                     alt="image" height="30px">
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($subChildCategory->status === 1)
                                                                <a class="action-button"
                                                                   onclick="return confirm('Disable this sub child category?')"
                                                                   href="{{route('status-category-admin',['type'=>'sub-child-category','id'=>$subChildCategory->id])}}"
                                                                   title="click to disable"><i
                                                                            class="fa fa-certificate text-success"> <i
                                                                                class="fa fa-check"></i></i></a>
                                                            @else
                                                                <a class="action-button"
                                                                   onclick="return confirm('Enable this sub child category')"
                                                                   href="{{route('status-category-admin',['type'=>'sub-child-category','id'=>$subChildCategory->id])}}"
                                                                   title="click to enable"><i
                                                                            class="fa fa-certificate text-danger"> <i
                                                                                class="fa fa-times"></i></i></a>
                                                            @endif

                                                            <a class="action-button edit-modal"
                                                               data-toggle="modal"
                                                               data-target="#editModal"
                                                               data-type="sub-child-category"
                                                               data-obj="{{$subChildCategory}}"><i
                                                                        class="fa fa-edit text-info"> {{__('dashboard.Edit')}}</i></a>

                                                        </td>
                                                    </tr>
                                                @empty
                                                @endforelse

                                            @empty
                                                {{--<tr>--}}
                                                {{--<td>No sub category added.</td>--}}
                                                {{--</tr>--}}
                                            @endforelse

                                            <tr>
                                                <td colspan="6"></td>
                                            </tr>
                                        @empty
                                            {{--<tr>--}}
                                            {{--<td>No category added.</td>--}}
                                            {{--</tr>--}}
                                        @endforelse
                                    </table>

                                    <!-- Add Modal -->
                                    <div class="modal fade" id="addModal" tabindex="-1"
                                         role="dialog" aria-labelledby="editModalLabel"
                                         aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">{{__('dashboard.Add')}} <span
                                                                id="modal-type"></span> {{__('dashboard.to')}} <b
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
                                                            {{__('dashboard.Name')}} <span
                                                                    class="m-l-5 text-danger">*</span>
                                                        </label>
                                                        <input type="text" name="name" class="form-control input-sm">
                                                        <br>
                                                        <div class="category-share">
                                                            <label>
                                                                {{__('dashboard.Category Share')}}
                                                            </label>
                                                            <input type="text" name="category_share"
                                                                   class="form-control input-sm">
                                                            <br>
                                                        </div>
                                                        <label>
                                                            {{__('dashboard.Image')}}
                                                        </label>
                                                        <input type="file" name="image">
                                                        <br>
                                                        <button type="submit"
                                                                class="btn btn-success">{{__('dashboard.Submit')}}</button>
                                                    </form>
                                                </div>
                                                <br>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">{{__('dashboard.Close')}}
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
                                                    <h4 class="modal-title">{{__('dashboard.Edit')}} <span
                                                                id="edit-modal-type"></span></h4>
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
                                                            {{__('dashboard.Name')}} <span
                                                                    class="m-l-5 text-danger">*</span>
                                                        </label>
                                                        <input type="text" name="name" id="edit-modal-name"
                                                               class="form-control input-sm">
                                                        <br>
                                                        <div class="category-share">
                                                            <label>
                                                                {{__('dashboard.Category Share')}}
                                                            </label>
                                                            <input type="text" name="category_share" id="edit-modal-category_share"
                                                                   class="form-control input-sm">
                                                            <br>
                                                        </div>
                                                        <label>
                                                            {{__('dashboard.Image')}}
                                                        </label>
                                                        <input type="file" name="image">
                                                        <br>
                                                        <div id="edit-modal-image">
                                                        </div>
                                                        <br>
                                                        <button type="submit"
                                                                class="btn btn-success">{{__('dashboard.Submit')}}</button>
                                                    </form>
                                                </div>
                                                <br>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">{{__('dashboard.Close')}}
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
        .action-button {
            float: right;
            font-size: 16px;
            margin-left: 20px;
        }

        .to-sub {
            /*display: none;*/
        }

        .add-category {
            font-size: 16px;
            float: right;
        }
    </style>
@stop
@section('scripts')
    <script src="{{ URL::asset('backend/js/admin/category-admin-1.js') }}" type="text/javascript"></script>
@stop