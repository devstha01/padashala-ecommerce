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
                                    <h4>{{__('dashboard.Add New Merchant')}}</h4>
                                </div>
                                <div class="portlet-body form">
                                    <form action="{{route('admin-merchant-register-post')}}" method="post"
                                          enctype="multipart/form-data">
                                        <div class="form-body">
                                            {{csrf_field()}}
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label class="control-label">{{__('dashboard.First Name')}}</label>
                                                        <input type="text" name="name"
                                                               class="form-control" value="{{old('name')??''}}">
                                                        <span style="color: red">{{$errors->first('name')??''}}</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">

                                                    <div class="form-group">
                                                        <label class="control-label">{{__('dashboard.Last Name')}}</label>
                                                        <input type="text" name="surname"
                                                               class="form-control" value="{{old('surname')??''}}">
                                                        <span style="color: red">{{$errors->first('surname')??''}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">

                                                    <div class="form-group">
                                                        <label class="control-label">{{__('dashboard.User Name')}}</label>
                                                        <input type="text" name="user_name"
                                                               class="form-control" value="{{old('user_name')??''}}">
                                                        <span style="color: red">{{$errors->first('user_name')??''}}</span>
                                                    </div>

                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label class="control-label">{{__('dashboard.Email')}}</label>
                                                        <input type="text" name="email"
                                                               class="form-control" value="{{old('email')??''}}">
                                                        <span style="color: red">{{$errors->first('email')??''}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label class="control-label">{{__('dashboard.Country')}}</label>
                                                        <select name="country_id" class="form-control">
                                                            <option value="">{{__('dashboard.-- select a country --')}}</option>
                                                            @forelse($countries as $country)
                                                                <option value="{{$country->id}}" {{$country->id == old('country_id')?'selected':''}}>{{$country->name}}</option>
                                                            @empty
                                                                <option value="">{{__('dashboard.no data available')}}</option>
                                                            @endforelse
                                                        </select>
                                                        <span style="color: red">{{$errors->first('country_id')??''}}</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label class="control-label">{{__('dashboard.City')}}</label>
                                                        <input type="text" name="city"
                                                               class="form-control" value="{{old('city')??''}}">
                                                        <span style="color: red">{{$errors->first('city')??''}}</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label class="control-label">{{__('dashboard.Address')}}</label>
                                                        <input type="text" name="address"
                                                               class="form-control" value="{{old('address')??''}}">
                                                        <span style="color: red">{{$errors->first('address')??''}}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label class="control-label">{{__('dashboard.Contact Number')}}</label>
                                                        <input type="text" name="contact_number"
                                                               class="form-control"
                                                               value="{{old('contact_number')??''}}">
                                                        <span style="color: red">{{$errors->first('contact_number')??''}}</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label class="control-label">{{__('dashboard.Date of Birth')}}</label>
                                                        <input class="form-control form-control-inline input-medium datepicker"
                                                               size="16" type="text" id="dob"
                                                               name="dob_date" autocomplete="off"
                                                               value="{{old('dob_date')??''}}">
                                                        <span style="color: red">{{$errors->first('dob_date')??''}}</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">

                                                    <div class="form-group">
                                                        <label class="control-label">{{__('dashboard.Gender')}}</label><br>
                                                        <input type="radio"
                                                               class="custom-control-input"
                                                               id="customControlValidation1"
                                                               name="gender" value="Male" checked>
                                                        <label class="custom-control-label"
                                                               for="customControlValidation1">{{__('dashboard.Male')}}</label>

                                                        <input type="radio"
                                                               class="custom-control-input"
                                                               id="customControlValidation2"
                                                               name="gender"
                                                               value="Female" {{old('gender') ==='Female' ? 'checked':''}}>
                                                        <label class="custom-control-label"
                                                               for="customControlValidation2">{{__('dashboard.Female')}}</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">

                                                    <div class="form-group">
                                                        <label class="control-label">{{__('dashboard.Marital Status')}}</label>
                                                        <select name="marital_status" class="form-control">
                                                            <option value="no" {{old('marital_status') === 'no'?'selected':''}}>
                                                                {{__('dashboard.Single')}}
                                                            </option>
                                                            <option value="yes" {{old('marital_status') ==='yes'?'selected':''}}>
                                                                {{__('dashboard.Married')}}
                                                            </option>
                                                        </select>
                                                        <span style="color: red">{{$errors->first('marital_status')??''}}</span>

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label class="control-label">{{__('dashboard.Identification Type')}}</label>
                                                        <select name="identification_type" class="form-control">
                                                            <option value="citizenship" {{old('identification_type')==='citizenship' ?'selected':''}}>
                                                                {{__('dashboard.citizenship')}}
                                                            </option>
                                                            <option value="passport" {{old('identification_type')==='passport' ?'selected':''}}>
                                                                {{__('dashboard.passport')}}
                                                            </option>
                                                        </select>
                                                        <span style="color: red">{{$errors->first('identification_type')??''}}</span>
                                                    </div>

                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label class="control-label">{{__('dashboard.Identification Number')}}
                                                        </label>
                                                        <input type="text"
                                                               class="form-control"
                                                               name="identification_number"
                                                               placeholder=""
                                                               value="{{old('identification_number')??''}}">
                                                        <span style="color: red">{{$errors->first('identification_number')??''}}</span>
                                                    </div>

                                                </div>
                                            </div>


                                            <hr>
                                            <h3>{{__('dashboard.Business')}}</h3>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">{{__('dashboard.Business Name')}}
                                                        </label>
                                                        <input type="text"
                                                               class="form-control"
                                                               name="business_name"
                                                               placeholder="" value="{{old('business_name')??''}}">
                                                        <span style="color: red">{{$errors->first('business_name')??''}}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">{{__('dashboard.Registration No.')}}
                                                        </label>
                                                        <input type="text"
                                                               class="form-control"
                                                               name="registration_number"
                                                               placeholder=""
                                                               value="{{old('registration_number')??''}}">
                                                        <span style="color: red">{{$errors->first('registration_number')??''}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">PAN No.
                                                        </label>
                                                        <input type="text"
                                                               class="form-control"
                                                               name="pan"
                                                               placeholder="" value="{{old('pan')??''}}">
                                                        <span style="color: red">{{$errors->first('pan')??''}}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">VAT No.
                                                        </label>
                                                        <input type="text"
                                                               class="form-control"
                                                               name="vat"
                                                               placeholder=""
                                                               value="{{old('vat')??''}}">
                                                        <span style="color: red">{{$errors->first('vat')??''}}</span>
                                                    </div>
                                                </div>

                                            </div>
                                            {{--<h3>{{__('dashboard.Profit Sharing')}}</h3>--}}
                                            {{--<div class="row">--}}
                                                {{--<div class="col-md-6">--}}
                                                    {{--<div class="form-group">--}}
                                                        {{--<label class="control-label">{{__('dashboard.Merchant Share')}}--}}
                                                        {{--</label>--}}
                                                        {{--<input type="text"--}}
                                                               {{--class="form-control"--}}
                                                               {{--name="merchant_share"--}}
                                                               {{--placeholder="" value="{{old('merchant_share')??''}}">--}}
                                                        {{--<span style="color: red">{{$errors->first('merchant_share')??''}}</span>--}}
                                                    {{--</div>--}}
                                                {{--</div>--}}
                                                {{--<div class="col-md-6">--}}
                                                    {{--<div class="form-group">--}}
                                                        {{--<label class="control-label">{{__('dashboard.Admin Share')}}--}}
                                                        {{--</label>--}}
                                                        {{--<input type="text"--}}
                                                               {{--class="form-control"--}}
                                                               {{--name="admin_share"--}}
                                                               {{--placeholder=""--}}
                                                               {{--value="{{old('admin_share')??''}}">--}}
                                                        {{--<span style="color: red">{{$errors->first('admin_share')??''}}</span>--}}
                                                    {{--</div>--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                            {{--<hr>--}}
                                            <h3>{{__('dashboard.Password')}}</h3>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">{{__('dashboard.Login Password')}}
                                                        </label>
                                                        <input type="password"
                                                               class="form-control"
                                                               name="new_password"
                                                               placeholder="" value="{{old('new_password')??''}}">
                                                        <span style="color: red">{{$errors->first('new_password')??''}}</span>
                                                    </div>


                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">{{__('dashboard.Retype Login Password')}}
                                                        </label>
                                                        <input type="password"
                                                               class="form-control"
                                                               name="retype_password"
                                                               placeholder="" value="{{old('retype_password')??''}}">
                                                        <span style="color: red">{{$errors->first('retype_password')??''}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="submit"
                                                    class="btn btn-success">{{__('dashboard.Submit')}}</button>
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
        var currentTime = new Date();
        $('#dob').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            startDate: moment().format('DD-MM') + '-' + Number(moment().format('YYYY') - 22),
            maxDate: moment().format('DD-MM') + '-' + Number(moment().format('YYYY') - 18),
            locale: {
                format: 'DD-MM-YYYY',
            },
        }, function (start, end, label) {
            var years = moment().diff(start, 'years');
            if (years < 18) {
                swal("You must be at least 18 years old.");
            }
        });

        // $('input[name="merchant_share"]').on('keyup', function () {
        //     var mer_share = $(this).val();
        //     if ($.isNumeric(mer_share) && mer_share < 100) {
        //         var sum100 = 100 - mer_share;
        //         $('input[name="admin_share"]').val(sum100);
        //     }
        // });
        //
        // $('input[name="admin_share"]').on('keyup', function () {
        //     var adm_share = $(this).val();
        //     if ($.isNumeric(adm_share) && adm_share < 100) {
        //         var sum100 = 100 - adm_share;
        //         $('input[name="merchant_share"]').val(sum100);
        //     }
        // })
    </script>
@stop
