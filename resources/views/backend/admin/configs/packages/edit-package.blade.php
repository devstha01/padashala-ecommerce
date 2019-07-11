
@extends('backend.layouts.master')
@section('content')
    <div class="page-wrapper-row full-height">
        <div class="page-wrapper-middle">
            <div class="page-container">
                <div class="container">

                    <div class="row">
                        @include('fragments.message')
                        <div class="col-md-12">
                            <br>
                            <div class="portlet light">
                                <div class="portlet-title">
                                    <h4> Package : {{ $package->name }}</h4>
                                    <a href="{{url('admin/config/packages')}}" class="btn btn-primary"> << {{__('dashboard.BACK')}}</a>
                                    <br>
                                    <br>
                                </div>
                                <div class="portlet-body form">
                                    <form action="{{route('edit-package')}}" method="post"
                                          enctype="multipart/form-data">
                                        <div class="form-body">
                                            {{csrf_field()}}
                                            <input type="hidden" name="pakId" value="{{$package->id}}">
                                            <div class="row">
                                                <div class="col-sm-3">

                                                    <div class="form-group">
                                                        <label class="control-label">Bid Coin</label>
                                                        <input type="text" name="bid_coin"
                                                               class="form-control" value="{{$package->bid_coin}}">
                                                        <span style="color: red">{{$errors->first('bid_coin')??''}}</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">

                                                    <div class="form-group">
                                                        <label class="control-label">Admin Percentage</label>
                                                        <input type="text" name="admin_percentage"
                                                               class="form-control" value="{{$package->admin_percentage}}">
                                                        <span style="color: red">{{$errors->first('admin_percentage')??''}}</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Ecash Percentage</label>
                                                        <input type="number" name="ecash_percentage"
                                                               class="form-control" value="{{$package->ecash_percentage}}">
                                                        <span style="color: red">{{$errors->first('ecash_percentage')??''}}</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Evoucher Percentage</label>
                                                        <input type="number" name="evoucher_percentage"
                                                               class="form-control" value="{{$package->evoucher_percentage}}">
                                                        <span style="color: red">{{$errors->first('evoucher_percentage')??''}}</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">

                                                    <div class="form-group">
                                                        <label class="control-label">Rpoint Percentage</label>
                                                        <input type="text" name="rpoint_percentage"
                                                               class="form-control" value="{{$package->rpoint_percentage}}">
                                                        <span style="color: red">{{$errors->first('rpoint_percentage')??''}}</span>
                                                    </div>
                                                </div>
                                            </div>


                                            <button type="submit" class="btn btn-success">{{__('dashboard.UPDATE')}}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

