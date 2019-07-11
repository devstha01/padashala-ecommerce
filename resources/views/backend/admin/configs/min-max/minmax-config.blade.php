@extends('backend.layouts.master')

@section('content')
    <div class="page-wrapper-row full-height">
        <div class="page-wrapper-middle">
            <div class="page-container">
                <div class="container">
                    <div class="row">
                        @include('fragments.message')
                        <div class="portlet light" style="min-height: 200px">
                            <h3>{{__('dashboard.Min-Max Config')}}</h3>
                            <div class="row">
                                <div class="col-sm-3">
                                    @include('backend.admin.configs.min-max.min-max-tab')
                                </div>
                                <div class="col-sm-9">
                                    <h3>{{ $name}}</h3>
                                    <br>
                                    {{ __('dashboard.Min Amount')}}:
                                    <span style="padding: 10px 20px; background: wheat">
                                ${{$config->min??0}}
                            </span>
                                    &nbsp;&nbsp;&nbsp;
                                    {{ __('dashboard.Max Amount')}}:
                                    <span style="padding: 10px 20px; background: wheat">
                            ${{$config->max??5000}}
                            </span>
                                    <button class="btn blue" id="withdraw-config-edit"><i
                                                class="fa fa-edit"></i>{{__('dashboard.Edit')}}</button>
                                    <hr>
                                    <div class="row hide" id="withdraw-edit-row">
                                        <div class="col-sm-12">
                                            <h3>{{ __('dashboard.Edit Withdrawal Config')}}</h3>
                                        </div>
                                        <form action="{{route('admin-withdraw-config-post')}}" method="post">
                                            {{csrf_field()}}

                                            <input type="hidden" name="type" value="{{$url}}">
                                            <input type="hidden" name="name" value="{{$name}}">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>{{ __('dashboard.Min Withdrawal Amount')}}</label>
                                                    <input type="text" name="min" value="{{$config->min??0}}"
                                                           class="form-control">
                                                    <span style="color: red">{{$errors->first('min')??''}}</span>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>{{ __('dashboard.Max Withdrawal Amount')}}</label>
                                                    <input type="text" name="max" value="{{$config->max??5000}}"
                                                           class="form-control">
                                                    <span style="color: red">{{$errors->first('max')??''}}</span>
                                                </div>
                                            </div>

                                            <button class="btn blue">{{__('dashboard.Submit')}}</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function () {
            $('#withdraw-config-edit').on('click', function () {
                $('#withdraw-edit-row').removeClass('hide');
            })
        })
    </script>
@endsection