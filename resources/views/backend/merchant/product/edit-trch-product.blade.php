@extends('backend.layouts.master')

@section('content')
    <div class="page-wrapper-row full-height">
        <div class="page-wrapper-middle">
            <div class="page-container">
                <div class="container">
                    <div class="row">
                        <br>
                        <div class="col-md-1"></div>
                        <div class="col-md-10">
                            <h3>{{__('dashboard.Product')}} - {{$product->name}}</h3>
                            @include('fragments.message')
                            <div class="tabbable-custom nav-justified">
                                <ul class="nav nav-tabs nav-justified">
                                    <li class="{{(session('active')!==null)?'':'active'}}">
                                        <a href="#tab_1_1_1" data-toggle="tab"> {{__('dashboard.General')}} </a>
                                    </li>
                                    <li class="{{(session('active') === 'variant')?'active':''}}">
                                        <a href="#tab_1_1_3" data-toggle="tab"> {{__('dashboard.Product Variants')}} </a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane {{(session('active')!==null)?'':'active'}}" id="tab_1_1_1">
                                        <form action="{{route('edit-product-tr-ch-merchant-post',$product->id)}}"
                                              method="post"
                                              enctype="multipart/form-data">
                                            {{csrf_field()}}

                                            <div class="row">
                                                <div class="col-md-6">

                                                    <div class="form-group">
                                                        <label>
                                                            {{__('dashboard.Name')}}
                                                        </label>
                                                        <input type="text" name="name" class="form-control input-sm"
                                                               value="{{$trch_product->name??''}}">

                                                        <span style="color: red">{{$errors->first('name')??''}}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label>
                                                    {{__('dashboard.Brief description')}}
                                                </label>
                                                <textarea class="form-control" name="detail"
                                                          style="resize: none">{{$trch_product->detail??''}}</textarea>


                                            </div>

                                            <div class="form-group">
                                                <label>
                                                    {{__('dashboard.Detail description')}}
                                                </label>
                                                <textarea class="form-control" name="description"
                                                          style="resize: none">{{$trch_product->description??''}}</textarea>

                                            </div>

                                            <button type="submit" class="btn btn-primary btn-lg"> save</button>
                                        </form>
                                    </div>

                                    <div class="tab-pane {{(session('active') === 'variant')?'active':''}}"
                                         id="tab_1_1_3">
                                        <table class="table tabl-striped">
                                            <tr>
                                                <th>#</th>
                                                <th>{{__('dashboard.Variant Name')}}</th>
                                                <th>{{__('dashboard.Chinese Variant Name')}}</th>
                                                <th>action</th>
                                            </tr>
                                            @forelse($product->getProductVariant as $key=>$variant)
                                                <tr>
                                                    <form action="{{route('edit-product-variant-tr-ch-merchant')}}"
                                                          method="post">
                                                        <td>{{++$key}}</td>
                                                        <td>{{$variant->name}}</td>
                                                        <td>
                                                            {{csrf_field()}}
                                                            <input type="hidden" name="id" value="{{$variant->id}}">
                                                            <input type="text" name="name"
                                                                   class="form-control"
                                                                   value="{{$variant->getTrChineseName->name??''}}">
                                                        </td>
                                                        <td>
                                                            <button type="submit" class="btn btn-info">{{__('dashboard.Change')}}</button>
                                                        </td>
                                                    </form>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4">{{__('dashboard.No variants added')}}.</td>
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
    </div>
@stop

@section('scripts')
    <script src="{{URL::asset('backend/assets/pages/scripts/ui-modals.min.js')}}" type="text/javascript"></script>
    <script src="{{URL::asset('backend/js/merchant/create-product-merchant.js')}}" type="text/javascript"></script>
@stop