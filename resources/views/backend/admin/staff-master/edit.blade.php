@extends('backend.layouts.master')
@section('stylesheets')
    <link href="{{asset('backend/assets/pages/css/profile.min.css')}}" rel="stylesheet" type="text/css"/>
@endsection

@section('content')
    <body class="page-container-bg-solid">
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
                                <h1>{{__('dashboard.Staff Profile')}}
                                    <small></small>
                                </h1>
                            </div>
                            <!-- END PAGE TITLE -->
                            <!-- BEGIN PAGE TOOLBAR -->

                        </div>
                    </div>
                    <!-- END PAGE HEAD-->
                    <!-- BEGIN PAGE CONTENT BODY -->
                    <div class="page-content">
                        <div class="container">
                        @include('fragments.message')

                        <!-- BEGIN PAGE CONTENT INNER -->
                            <div class="page-content-inner">
                                <div class="row">
                                    <div class="col-md-12">
                                        <!-- BEGIN PROFILE SIDEBAR -->


                                        <div class="profile-content">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <!-- BEGIN PORTLET -->
                                                    <div class="portlet light ">
                                                        <div class="portlet-title">
                                                            <div class="caption caption-md">
                                                                <i class="icon-bar-chart theme-font hide"></i>
                                                                <span class="caption-subject font-blue-madison bold uppercase">{{__('dashboard.Edit Profile')}}</span>
                                                            </div>
                                                            <div class="actions">
                                                                <div class="btn-group btn-group-devided"
                                                                     data-toggle="buttons">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="portlet-body">
                                                            <ul class="nav nav-tabs">
                                                                <li class="{{empty(session('edit-profile-staff'))?'active':''}}">
                                                                    <a href="#tab_1_1"
                                                                       data-toggle="tab">{{__('dashboard.Personal Info')}}</a>
                                                                </li>

                                                                <li class="{{(session('edit-profile-staff') === 'pass')?'active':''}}">
                                                                    <a href="#tab_1_3"
                                                                       data-toggle="tab">{{__('dashboard.Change Password')}}</a>
                                                                </li>
                                                            </ul>

                                                            <div class="tab-content">
                                                                <!-- PERSONAL INFO TAB -->
                                                                <div class="tab-pane {{empty(session('edit-profile-staff'))?'active':''}}"
                                                                     id="tab_1_1">

                                                                    <form action="{{route('submit-staff-profile-edit',$admin->id)}}"
                                                                          method="post"
                                                                          enctype="multipart/form-data">
                                                                        {{csrf_field()}}
                                                                        <div class="row">
                                                                            <div class="col-sm-6">
                                                                                <div class="form-group">
                                                                                    <label class="control-label">{{__('dashboard.First Name')}}</label>
                                                                                    <input type="text" name="name"
                                                                                           class="form-control"
                                                                                           value="{{$admin->name??''}}">
                                                                                    <span style="color: red">{{$errors->first('name')??''}}</span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-6">

                                                                                <div class="form-group">
                                                                                    <label class="control-label">{{__('dashboard.Last Name')}}</label>
                                                                                    <input type="text"
                                                                                           name="surname"
                                                                                           class="form-control"
                                                                                           value="{{$admin->surname??''}}">
                                                                                    <span style="color: red">{{$errors->first('surname')??''}}</span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-sm-6">

                                                                                <div class="form-group">
                                                                                    <label class="control-label">{{__('dashboard.User Name')}}</label>
                                                                                    <input type="text"
                                                                                           name="user_name"
                                                                                           class="form-control"
                                                                                           value="{{$admin->user_name??''}}">
                                                                                    <span style="color: red">{{$errors->first('user_name')??''}}</span>
                                                                                </div>

                                                                            </div>
                                                                            <div class="col-sm-6">
                                                                                <div class="form-group">
                                                                                    <label class="control-label">{{__('dashboard.Email')}}</label>
                                                                                    <input type="text" name="email"
                                                                                           class="form-control"
                                                                                           value="{{$admin->email??''}}">
                                                                                    <span style="color: red">{{$errors->first('email')??''}}</span>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-sm-6">
                                                                                <div class="form-group">
                                                                                    <label class="control-label">{{Lang::get('dashboard.Position')}}</label>
                                                                                    <input type="text" name="position"
                                                                                           class="form-control"
                                                                                           value="{{$admin->position??''}}">
                                                                                    <span style="color: red">{{$errors->first('position')??''}}</span>
                                                                                </div>
                                                                            </div>

                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-sm-4">
                                                                                <div class="form-group">
                                                                                    <label class="control-label">{{__('dashboard.Country')}}</label>
                                                                                    <select name="country_id"
                                                                                            class="form-control">
                                                                                        <option value="">{{__('dashboard.-- select a country --')}}</option>
                                                                                        @forelse($countries as $country)
                                                                                            <option value="{{$country->id}}" {{$country->id == $admin->country_id?'selected':''}}>{{$country->name}}</option>
                                                                                        @empty
                                                                                            <option value="">{{__('dashboard.no data available')}}</option>
                                                                                        @endforelse
                                                                                    </select>
                                                                                    <span style="color: red">{{$errors->first('country_id')??''}}</span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-8">
                                                                                <div class="form-group">
                                                                                    <label class="control-label">{{__('dashboard.Address')}}</label>
                                                                                    <input type="text"
                                                                                           name="address"
                                                                                           class="form-control"
                                                                                           value="{{$admin->address??''}}">
                                                                                    <span style="color: red">{{$errors->first('address')??''}}</span>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="row">
                                                                            <div class="col-sm-3">
                                                                                <div class="form-group">
                                                                                    <label class="control-label">{{__('dashboard.Contact Number')}}</label>
                                                                                    <input type="text"
                                                                                           name="contact_number"
                                                                                           class="form-control"
                                                                                           value="{{$admin->contact_number??''}}">
                                                                                    <span style="color: red">{{$errors->first('contact_number')??''}}</span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-4">
                                                                                <div class="form-group">
                                                                                    <label class="control-label">{{__('dashboard.Date of Birth')}}</label>
                                                                                    <input class="form-control form-control-inline input-medium datepicker"
                                                                                           size="16" type="text"
                                                                                           name="dob_date"
                                                                                           autocomplete="off"
                                                                                           value="{{\Carbon\Carbon::parse($admin->dob)->format('d-m-Y')}}">
                                                                                    <span style="color: red">{{$errors->first('dob_date')??''}}</span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-2">

                                                                                <div class="form-group">
                                                                                    <label class="control-label">{{__('dashboard.Gender')}}</label><br>
                                                                                    <input type="radio"
                                                                                           class="custom-control-input"
                                                                                           id="customControlValidation1"
                                                                                           name="gender"
                                                                                           value="Male" checked>
                                                                                    <label class="custom-control-label"
                                                                                           for="customControlValidation1">{{__('dashboard.Male')}}</label>

                                                                                    <input type="radio"
                                                                                           class="custom-control-input"
                                                                                           id="customControlValidation2"
                                                                                           name="gender"
                                                                                           value="Female" {{$admin->gender ==='Female' ? 'checked':''}}>
                                                                                    <label class="custom-control-label"
                                                                                           for="customControlValidation2">{{__('dashboard.Female')}}</label>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-3">

                                                                                <div class="form-group">
                                                                                    <label class="control-label">{{__('dashboard.Marital Status')}}</label>
                                                                                    <select name="marital_status"
                                                                                            class="form-control">
                                                                                        <option value="no" {{$admin->marital_status === 'no'?'selected':''}}>
                                                                                            {{__('dashboard.Single')}}
                                                                                        </option>
                                                                                        <option value="yes" {{$admin->marital_status ==='yes'?'selected':''}}>
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
                                                                                    <select name="identification_type"
                                                                                            class="form-control">
                                                                                        <option value="citizenship" {{$admin->identification_type==='citizenship' ?'selected':''}}>
                                                                                            {{__('dashboard.citizenship')}}
                                                                                        </option>
                                                                                        <option value="passport" {{$admin->identification_type==='passport' ?'selected':''}}>
                                                                                            {{__('dashboard.passport')}}
                                                                                        </option>
                                                                                    </select>
                                                                                    <span style="color: red">{{$errors->first('identification_type')??''}}</span>
                                                                                </div>

                                                                            </div>
                                                                            <div class="col-sm-6">
                                                                                <div class="form-group">
                                                                                    <label class="control-label">{{__('dashboard.Identification Number')}}</label>
                                                                                    <input type="text"
                                                                                           class="form-control"
                                                                                           name="identification_number"
                                                                                           placeholder=""
                                                                                           value="{{$admin->identification_number??''}}">
                                                                                    <span style="color: red">{{$errors->first('identification_number')??''}}</span>
                                                                                </div>

                                                                            </div>
                                                                        </div>

                                                                        <hr>


                                                                        <button type="submit"
                                                                                class="btn btn-success">{{__('dashboard.Submit')}}
                                                                        </button>
                                                                        <br>
                                                                        <br>
                                                                    </form>
                                                                </div>
                                                                <div class="tab-pane {{(session('edit-profile-staff') === 'pass')?'active':''}}"
                                                                     id="tab_1_3">
                                                                    <h3>{{__('dashboard.Password')}}</h3>
                                                                    <div class="row">
                                                                        <form action="{{route('submit-staff-pass',$admin->id)}}"
                                                                              method="post">
                                                                            {{csrf_field()}}
                                                                            <div class="col-md-6">


                                                                                <div class="form-group">
                                                                                    <label class="control-label">{{__('dashboard.New Password')}}
                                                                                    </label>
                                                                                    <input type="password"
                                                                                           class="form-control"
                                                                                           name="new_password">
                                                                                    <span style="color: red">{{$errors->first('new_password')??''}}</span>
                                                                                    <span style="color: red">{{session('new_password')??''}}</span>
                                                                                </div>

                                                                                <div class="form-group">
                                                                                    <label class="control-label">{{__('dashboard.Retype Password')}}
                                                                                    </label>
                                                                                    <input type="password"
                                                                                           class="form-control"
                                                                                           name="retype_password">
                                                                                    <span style="color: red">{{$errors->first('retype_password')??''}}</span>
                                                                                </div>

                                                                                <button type="submit"
                                                                                        class="btn btn-success">{{__('dashboard.Submit')}}
                                                                                </button>
                                                                            </div>
                                                                            </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- END PORTLET -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END PAGE CONTENT INNER -->
                        </div>
                    </div>
                    <!-- END PAGE CONTENT BODY -->
                    <!-- END CONTENT BODY -->
                </div>
            </div>
        </div>
    </div>
    <!-- END CONTENT -->
@stop


    @section('scripts')
        <script>

            var currentTime = new Date();
            $('input[name="dob_date"].datepicker').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                // startDate: moment().format('DD-MM') + '-' + Number(moment().format('YYYY') - 22),
                // maxDate: moment().format('DD-MM') + '-' + Number(moment().format('YYYY') - 18),
                locale: {
                    format: 'DD-MM-YYYY',
                },
            });
        </script>
@stop