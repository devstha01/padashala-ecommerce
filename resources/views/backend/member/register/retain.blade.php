
@extends('backend.layouts.master')
@section('content')
    <div class="page-wrapper-row full-height">
        <div class="page-wrapper-middle">
            <div class="page-container">
                <div class="container">

                    <div class="row">
                        @include('fragments.message')<br>
                        @if(session('retainSuccess'))
                            <div class="alert alert-success alert-dismissible">
                                <h4><i class="icon fa fa-check"></i> {{session('retainSuccess')}}</h4>
                            </div>
                            @else
                            <div class="col-md-12">
                                <br>
                                <div class="portlet light">
                                    <div class="portlet-title">
                                        <h4>Retain Wallet For : <strong style="font-size: 24px">{{$user->user_name}}</strong> </h4>
                                        <a href="{{url('admin/member-profile/'.$user->id)}}" class="btn btn-primary"><i class="fa fa-backward"></i> {{__('dashboard.BACK')}}</a>
                                        <br>
                                        <br>
                                    </div>
                                    <div class="portlet-body form">

                                        {{ Form::open([
                                              'url' => 'admin/retain-member-wallet',
                                              'id' => 'grantForm',
                                               'class' => 'horizontal-form ajax-post-retain',
                                                'method'=> 'POST',
                                                 'autocomplete' => 'off'
                                             ])
                                       }}
                                        <div class="form-body">
                                            {{csrf_field()}}
                                            <input type="hidden" name="userId" value="{{$user->id}}">
                                            <input type="hidden" name="memberName" value="{{$user->name}}">
                                            <div class="row">

                                                <div class="col-sm-6">

                                                    <div class="form-group">
                                                        <label class="control-label">Wallet Type</label>
                                                        {{ Form::select('wallet_type', $walletType ,null, ['class'=> 'form-control', 'placeholder' => 'Choose Wallet Type','required']) }}
                                                        <span style="color: red">{{$errors->first('wallet_type')??''}}</span>
                                                        <span class="error-message"></span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">

                                                    <div class="form-group">
                                                        <label class="control-label">Wallet Value</label>
                                                        <input type="number" name="wallet_value"
                                                               class="form-control" value="{{old('wallet_value')??''}}" required>
                                                        <span style="color: red">{{$errors->first('wallet_value')??''}}</span>
                                                        <span class="error-message"></span>
                                                    </div>
                                                </div>
                                            </div>


                                            <button type="submit" class="btn btn-success">{{__('dashboard.Submit')}}</button>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

