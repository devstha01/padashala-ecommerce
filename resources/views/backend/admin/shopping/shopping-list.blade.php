@extends('backend.layouts.master')

@section('content')
    <div class="page-wrapper-row full-height">
        <div class="page-wrapper-middle">
            <div class="page-container">
                <div class="container">
                    <div class="row">
                        <h3>{{__('dashboard.Shopping Bonus')}}</h3>
                        <div class="col-md-12">
                            <div class="portlet light">
                                @include('fragments.message')
                                <div class="tabbable-custom nav-justified">
                                    <ul class="nav nav-tabs nav-justified">
                                        <li class="{{empty(session('active-tab'))?'active':''}}">
                                            <a href="#tab_1_1"
                                               data-toggle="tab">{{__('dashboard.Shopping Bonus Rates')}}</a>
                                        </li>

                                        <li class="{{session('active-tab') ==='standard'?'active':''}}"><a
                                                    href="#tab_1_2"
                                                    data-toggle="tab"> {{__('dashboard.Generation Bonus')}} </a></li>
                                    </ul>

                                    <div class="tab-content">
                                        <!-- PERSONAL INFO TAB -->
                                        <div class="tab-pane {{empty(session('active-tab'))?'active':''}}"
                                             id="tab_1_1">@include('backend.admin.shopping.bonus-rates')</div>
                                        <div class="tab-pane {{session('active-tab') ==='standard'?'active':''}}"
                                             id="tab_1_2">
                                            @include('backend.admin.shopping.single-shopping-bonus')
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                    {{--<!-- Button trigger modal -->--}}
                    {{--<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#shoppingMerchantModal">--}}
                    {{--Launch demo modal--}}
                    {{--</button>--}}

                    <!-- Modal -->
                        <div class="modal fade" id="shoppingMerchantModal" tabindex="-1" role="dialog"
                             aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title"
                                            id="shoppingMerchantModalLabel">{{__('dashboard.Change Merchant Rate')}}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="{{route('edit-merchant-rate')}}" method="post">
                                        {{csrf_field()}}
                                        <input type="hidden" name="merchant_id">
                                        <div class="modal-body">
                                            <h5>
                                                <span>{{__('dashboard.User Name')}} : </span>
                                                <span id="shopping-username"></span>
                                            </h5>
                                            <span style="color: red">{{$errors->first('merchant_id')??''}}</span>

                                            <div class="row">
                                                <div class="col-sm-6 form-group">
                                                    <label>{{__('dashboard.Merchant Rate')}}:</label>
                                                    <input type="text" class="form-control" name="merchant_rate">
                                                    <span style="color: red">{{$errors->first('merchant_rate')??''}}</span>
                                                </div>
                                                <div class="col-sm-6 form-group">
                                                    <label>{{__('dashboard.Admin Rate')}}:</label>
                                                    <input type="text" class="form-control" name="admin_rate">
                                                    <span style="color: red">{{$errors->first('admin_rate')??''}}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn blue"
                                                    data-dismiss="modal">{{__('dashboard.Close')}}</button>
                                            <button type="submit" class="btn blue mr-2"
                                                    style="margin-right:20px">{{__('dashboard.Save')}}
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <button class="fade btn blue" id="trigger_modal"
                                data-id="{{$errors->first('id')}}"></button>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('stylesheets')
    <style>
        .shopping-row {
            cursor: pointer;
        }

        .shopping-content, .update-rate {
            padding: 2px 10px;
        }
    </style>
@endsection
@section('scripts')
    <script src="{{asset('backend/js/admin/shopping-list.js')}}"></script>
@endsection