@extends('backend.layouts.master')
@section('content')
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
                                <h1>{{ __('dashboard.Bank Info') }}
                                </h1>
                            </div>
                        </div>
                    </div>

                    <div class="page-content">
                        <div class="container">
                            @include('backend.merchant.merchant-wallet-card')
                        </div>
                    </div>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-md-12">
                                <div class="heading">
                                    <h2 class="title">{{__('front.Edit Bank Info')}}</h2>
                                </div><!-- End .heading -->
                                @include('fragments.message')
                                <form action="{{route('merchant-update-bank')}}" method="post">
                                    {{csrf_field()}}
                                    <h4>{{__('front.BANK DETAIL')}}</h4>
                                    <div class="row">
                                        <div class="col-sm-12 text-right">
                                            {{--<a href="{{route('member/dashboard')}}" class="btn "><i class="fa fa-dashboard fa-2x"></i> {{__('front.Go to Dashboard')}}</a>--}}
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="control-label">{{__('front.Bank Name')}}</label>
                                                <input type="text" name="bank_name"
                                                       class="form-control" value="{{($banks->bank_name??'')}}">
                                                <span style="color: red">{{$errors->first('bank_name')??''}}</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">

                                            <div class="form-group">
                                                <label class="control-label">{{__('front.Account Name')}}</label>
                                                <input type="text" name="acc_name"
                                                       class="form-control" value="{{($banks->acc_name??'')}}">
                                                <span style="color: red">{{$errors->first('acc_name')??''}}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">

                                            <div class="form-group">
                                                <label class="control-label">{{__('front.Your Contact')}}</label>
                                                <input type="text" class="form-control" name="contact_number"
                                                       value="{{($banks->contact_number??'')}}">
                                                <span style="color: red">{{$errors->first('contact_number')??''}}</span>
                                            </div>

                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="control-label">{{__('front.Account Number')}}</label>
                                                <input type="text" name="acc_number"
                                                       class="form-control" value="{{($banks->acc_number??'')}}">
                                                <span style="color: red">{{$errors->first('acc_number')??''}}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-footer">
                                        @if(!session('update'))
                                            <button type="submit"
                                                    class="btn btn-primary">{{__('front.Submit')}}</button>
                                        @endif
                                    </div><!-- End .form-footer -->
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection