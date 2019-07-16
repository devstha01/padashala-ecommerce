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
                                    <h4>{{__('dashboard.Add new Staff')}}</h4>
                                </div>
                                <div class="portlet-body form">
                                    <form action="{{route('admin-staff-register-post')}}" method="post"
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
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label class="control-label">{{__('dashboard.Position')}}</label>
                                                        <input type="text" name="position"
                                                               class="form-control" value="{{old('position')??''}}">
                                                        <span style="color: red">{{$errors->first('position')??''}}</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label class="control-label">{{__('dashboard.Initialize Permission')}}</label>

                                                        <select name="permission" class="form-control"
                                                                style="border: 1px solid grey">
                                                            <option value="none">
                                                                {{__('dashboard.No Permission')}}
                                                            </option>
                                                            <option value="view">
                                                                {{__('dashboard.View Only Permissions')}}
                                                            </option>
                                                            <option value="all">
                                                                {{__('dashboard.All Permissions')}}
                                                            </option>

                                                        </select>
                                                        <span style="color: red">{{$errors->first('role')??''}}</span>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label class="control-label">{{__('dashboard.Country')}}</label>
                                                        <select name="country_id" class="form-control">
                                                            <option value="">{{__('dashboard.-- select a country --')}}</option>
                                                            @forelse($countries as $country)
                                                                <option value="{{$country->id}}" {{$country->id == old('country_id')?'selected':''}}>{{$country->name}}</option>
                                                            @empty
                                                                <option value=""> {{__('dashboard.no data available')}}</option>
                                                            @endforelse
                                                        </select>
                                                        <span style="color: red">{{$errors->first('country_id')??''}}</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-8">
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
                                                               size="16" type="text"
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
                                            </div>    <hr>
                                            <h3>{{__('dashboard.Password')}}</h3>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    {{--                                                    <h5>{{__('dashboard.Primary Password')}}</h5>--}}
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
        $('input[name="dob_date"].datepicker').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            startDate: moment().format('DD-MM') + '-' + Number(moment().format('YYYY') - 22),
            maxDate: moment().format('DD-MM') + '-' + Number(moment().format('YYYY') - 18),
            locale: {
                format: 'DD-MM-YYYY',
            },
        });
    </script>
@stop