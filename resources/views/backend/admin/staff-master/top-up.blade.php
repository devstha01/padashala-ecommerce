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
                                    <h4>Top up wallet</h4>
                                </div>
                                <div class="portlet-body form">
                                    <form action="{{route('top-up-post')}}" method="post"
                                          enctype="multipart/form-data">
                                        <div class="form-body">
                                            {{csrf_field()}}
                                            <div class="row">
                                                <div class="col-sm-3"></div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Topup wallet on </label>
                                                        <select name="type" class="form-control">
                                                            <option value="customer">Customer</option>
                                                            <option value="merchant">Merchant</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3"></div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Username</label>
                                                        <input type="text" name="user_name"
                                                               class="form-control" value="{{old('user_name')??''}}">
                                                        <span style="color: red">{{$errors->first('user_name')??''}}</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Amount</label>
                                                        <input type="text" name="amount"
                                                               class="form-control" value="{{old('amount')??''}}">
                                                        <span style="color: red">{{$errors->first('amount')??''}}</span>
                                                    </div>
                                                </div>
                                            </div>


                                            <button type="submit"
                                                    class="btn btn-success">{{__('dashboard.Submit')}}</button>

                                            <br>
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

@section('scripts')
    <script>

    </script>
@stop