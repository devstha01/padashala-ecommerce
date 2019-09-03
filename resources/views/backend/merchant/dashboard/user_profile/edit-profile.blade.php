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
                                <h1>{{__('dashboard.Profile')}}
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
                        <!-- BEGIN PAGE BREADCRUMBS -->
                        {{--<ul class="page-breadcrumb breadcrumb">--}}
                        {{--<li>--}}
                        {{--<a href="">Home</a>--}}
                        {{--<i class="fa fa-circle"></i>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                        {{--<a href="{{route('merchant-profile')}}">Profile</a>--}}
                        {{--<i class="fa fa-circle"></i>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                        {{--<span>User</span>--}}
                        {{--</li>--}}
                        {{--</ul>--}}
                        <!-- END PAGE BREADCRUMBS -->
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
                                                                <div class="btn-group btn-group-devided">
                                                                    <form action="{{route('regen-merchant-qr')}}"
                                                                          method="post" class="float-right">
                                                                        {{csrf_field()}}
                                                                        <input type="hidden" name="merchant_id"
                                                                               value="{{Auth::guard('merchant')->id()}}">
                                                                        <button type="submit" class="btn blue"><i
                                                                                    class="fa fa-qrcode"> </i>
                                                                            Regenerate QR Code
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="portlet-body">
                                                            <ul class="nav nav-tabs">
                                                                <li class="{{empty(session('edit-profile-merchant'))?'active':''}}">
                                                                    <a href="#tab_1_1"
                                                                       data-toggle="tab">{{__('dashboard.Personal Info')}}</a>
                                                                </li>


                                                                <li class="{{(session('edit-profile-merchant') === 'image')?'active':''}}">
                                                                    <a href="#tab_1_2"
                                                                       data-toggle="tab">Change Logo</a>
                                                                </li>
                                                                <li class="{{(session('edit-profile-merchant') === 'documents')?'active':''}}">
                                                                    <a href="#tab_1_4"
                                                                       data-toggle="tab">Documents</a>
                                                                </li>

                                                                <li class="{{(session('edit-profile-merchant') === 'pass')?'active':''}}">
                                                                    <a href="#tab_1_3"
                                                                       data-toggle="tab">{{__('dashboard.Change Password')}}</a>
                                                                </li>
                                                            </ul>

                                                            <div class="tab-content">
                                                                <!-- PERSONAL INFO TAB -->
                                                                <div class="tab-pane {{empty(session('edit-profile-merchant'))?'active':''}}"
                                                                     id="tab_1_1">

                                                                    <form action="{{route('merchant-submit-merchant-profile-edit')}}"
                                                                          method="post"
                                                                          enctype="multipart/form-data">
                                                                        {{csrf_field()}}
                                                                        <div class="row">
                                                                            <div class="col-sm-6">
                                                                                <div class="form-group">
                                                                                    <label class="control-label">{{__('dashboard.First Name')}}</label>
                                                                                    <input type="text" name="name"
                                                                                           class="form-control"
                                                                                           value="{{$merchant->name??''}}">
                                                                                    <span style="color: red">{{$errors->first('name')??''}}</span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-6">

                                                                                <div class="form-group">
                                                                                    <label class="control-label">{{__('dashboard.Last Name')}}</label>
                                                                                    <input type="text"
                                                                                           name="surname"
                                                                                           class="form-control"
                                                                                           value="{{$merchant->surname??''}}">
                                                                                    <span style="color: red">{{$errors->first('surname')??''}}</span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-sm-6">
                                                                                <div class="form-group">
                                                                                    <label class="control-label">{{__('dashboard.Email')}}</label>
                                                                                    <input type="text" name="email"
                                                                                           class="form-control"
                                                                                           value="{{$merchant->email??''}}">
                                                                                    <span style="color: red">{{$errors->first('email')??''}}</span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-6">
                                                                                <div class="form-group">
                                                                                    <label class="control-label">{{__('dashboard.Country')}}</label>
                                                                                    <select name="country_id"
                                                                                            class="form-control">
                                                                                        <option value="">{{__('dashboard.-- select a country --')}}</option>
                                                                                        @forelse($countries as $country)
                                                                                            <option value="{{$country->id}}" {{$country->id == $merchant->country_id?'selected':''}}>{{$country->name}}</option>
                                                                                        @empty
                                                                                            <option value=""> {{__('dashboard.no data available')}}
                                                                                            </option>
                                                                                        @endforelse
                                                                                    </select>
                                                                                    <span style="color: red">{{$errors->first('country_id')??''}}</span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-sm-6">

                                                                                <div class="form-group">
                                                                                    <label class="control-label">{{__('dashboard.City')}}</label>
                                                                                    <input type="text"
                                                                                           name="city"
                                                                                           class="form-control"
                                                                                           value="{{$merchant->city??''}}">
                                                                                    <span style="color: red">{{$errors->first('city')??''}}</span>
                                                                                </div>

                                                                            </div>
                                                                            <div class="col-sm-6">
                                                                                <div class="form-group">
                                                                                    <label class="control-label">{{__('dashboard.Address')}}</label>
                                                                                    <input type="text"
                                                                                           name="address"
                                                                                           class="form-control"
                                                                                           value="{{$merchant->address??''}}">
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
                                                                                           value="{{$merchant->contact_number??''}}">
                                                                                    <span style="color: red">{{$errors->first('contact_number')??''}}</span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-4">
                                                                                <div class="form-group">
                                                                                    <label class="control-label">{{__('dashboard.Date of Birth')}}</label>
                                                                                    <input class="form-control form-control-inline input-medium date-picker"
                                                                                           size="16" type="text"
                                                                                           name="dob_date" id="dob"
                                                                                           autocomplete="off"
                                                                                           value="{{\Carbon\Carbon::parse($merchant->dob)->format('d-m-Y')}}">
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
                                                                                           value="Female" {{$merchant->gender ==='Female' ? 'checked':''}}>
                                                                                    <label class="custom-control-label"
                                                                                           for="customControlValidation2">{{__('dashboard.Female')}}</label>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-3">

                                                                                <div class="form-group">
                                                                                    <label class="control-label">{{__('dashboard.Marital Status')}}</label>
                                                                                    <select name="marital_status"
                                                                                            class="form-control">
                                                                                        <option value="no" {{$merchant->marital_status === 'no'?'selected':''}}>
                                                                                            {{__('dashboard.Single')}}
                                                                                        </option>
                                                                                        <option value="yes" {{$merchant->marital_status ==='yes'?'selected':''}}>
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
                                                                                        <option value="citizenship" {{$merchant->identification_type==='citizenship' ?'selected':''}}>
                                                                                            {{__('dashboard.citizenship')}}
                                                                                        </option>
                                                                                        <option value="passport" {{$merchant->identification_type==='passport' ?'selected':''}}>
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
                                                                                           value="{{$merchant->identification_number??''}}">
                                                                                    <span style="color: red">{{$errors->first('identification_number')??''}}</span>
                                                                                </div>

                                                                            </div>
                                                                        </div>
                                                                        {{--<div class="form-group">--}}
                                                                        {{--<label class="control-label">{{__('dashboard.Joining Date')}}</label>--}}
                                                                        {{--<input class="form-control form-control-inline input-medium date-picker"--}}
                                                                        {{--size="16" type="text"--}}
                                                                        {{--name="joining_date"--}}
                                                                        {{--value="{{$merchant->joining_date??''}}"--}}
                                                                        {{--autocomplete="off">--}}
                                                                        {{--<span style="color: red">{{$errors->first('joining_date')??''}}</span>--}}
                                                                        {{--</div>--}}

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
                                                                                           placeholder=""
                                                                                           value="{{$merchant->getBusiness->name??''}}">
                                                                                    <span style="color: red">{{$errors->first('business_name')??''}}</span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label class="control-label">{{__('dashboard.Registration no')}}
                                                                                        .
                                                                                    </label>
                                                                                    <input type="text"
                                                                                           class="form-control"
                                                                                           name="registration_number"
                                                                                           placeholder=""
                                                                                           value="{{$merchant->getBusiness->registration_number??''}}">
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
                                                                                           placeholder=""
                                                                                           value="{{$merchant->getBusiness->pan??''}}">
                                                                                    <span style="color: red">{{$errors->first('pan')??''}}</span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label class="control-label">VAT No.
                                                                                        .
                                                                                    </label>
                                                                                    <input type="text"
                                                                                           class="form-control"
                                                                                           name="vat"
                                                                                           placeholder=""
                                                                                           value="{{$merchant->getBusiness->vat??''}}">
                                                                                    <span style="color: red">{{$errors->first('vat')??''}}</span>
                                                                                </div>

                                                                            </div>
                                                                        </div>

                                                                        <button type="submit"
                                                                                class="btn btn-success">{{__('dashboard.Submit')}}
                                                                        </button>

                                                                    </form>
                                                                    <br>
                                                                    <br>
                                                                </div>

                                                                <div class="tab-pane {{(session('edit-profile-merchant') === 'image')?'active':''}}"
                                                                     id="tab_1_2">

                                                                    <h4>Business Logo</h4>
                                                                    <div class="row">
                                                                        <div class="col-sm-6">

                                                                            <form action="{{route('merchant-submit-image-edit',$merchant->id)}}"
                                                                                  method="post"
                                                                                  enctype="multipart/form-data">
                                                                                {{csrf_field()}}

                                                                                <div class="form-group">
                                                                                    <input type="file"
                                                                                           class="form-control"
                                                                                           name="logo">
                                                                                </div>
                                                                                <br>
                                                                                @if($merchant->logo !== null)
                                                                                    <img src="{{asset('image/merchantlogo/'.$merchant->logo)}}"
                                                                                         alt="logo"
                                                                                         style="height: 100px">

                                                                                @endif

                                                                                <button type="submit"
                                                                                        class="btn btn-primary"> {{__('dashboard.Submit')}}
                                                                                </button>

                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="tab-pane {{(session('edit-profile-merchant') === 'documents')?'active':''}}"
                                                                     id="tab_1_4">

                                                                    <h4>Business Documents</h4>

                                                                    <form action="{{route('merchant-submit-doc')}}"
                                                                          method="post"
                                                                          enctype="multipart/form-data">
                                                                        {{csrf_field()}}
                                                                        <div class="row">
                                                                            <div class="col-sm-6">
                                                                                <div class="form-group">
                                                                                    <input type="file"
                                                                                           name="file"
                                                                                           class="form-control"
                                                                                           required>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-6">
                                                                                <div class="form-group">
                                                                                    <input type="text"
                                                                                           name="name"
                                                                                           class="form-control">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <button type="submit"
                                                                                class="btn btn-primary"> {{__('dashboard.Submit')}}
                                                                        </button>
                                                                        <br>
                                                                        <br>
                                                                        <br>
                                                                    </form>

                                                                    <hr>
                                                                    <table class="table table-hover">
                                                                        <tr>
                                                                            <th>S/N</th>
                                                                            <th>Name</th>
                                                                            <th>File</th>
                                                                            <th>Type</th>
                                                                            <th>Action</th>
                                                                        </tr>
                                                                        @forelse($merchant->documents as $key=>$document)
                                                                            <tr>
                                                                                <td>{{++$key}}</td>
                                                                                <td>{{$document->name}}</td>
                                                                                <td><a href="{{asset('image/merchant_documents/'.$document->file)}}"> <i class="fa fa-file"></i></a></td>
                                                                                <td>{{$document->mime}}</td>
                                                                                <td>
                                                                                    <form action="{{route('delete-doc',$document->id)}}" method="post">
                                                                                        {{csrf_field()}}
                                                                                        <button class="btn blue">Delete</button>
                                                                                    </form>
                                                                                </td>
                                                                            </tr>
                                                                        @empty
                                                                            <tr>
                                                                                <td colspan="5">No files uploaded
                                                                                </td>
                                                                            </tr>
                                                                        @endforelse
                                                                    </table>
                                                                </div>

                                                                <div class="tab-pane {{(session('edit-profile-merchant') === 'pass')?'active':''}}"
                                                                     id="tab_1_3">
                                                                    <h3>{{__('dashboard.Password')}}</h3>
                                                                    <div class="row">
                                                                        <form action="{{route('merchant-submit-merchant-pass')}}"
                                                                              method="post">
                                                                            {{csrf_field()}}
                                                                            <div class="col-md-6">
                                                                                {{--                                                                                <h5>{{__('dashboard.Primary Password')}}</h5>--}}

                                                                                <div class="form-group">
                                                                                    <label class="control-label">{{__('dashboard.Login Password')}}
                                                                                    </label>
                                                                                    <input type="password"
                                                                                           class="form-control"
                                                                                           name="old_password">
                                                                                    <span style="color: red">{{$errors->first('old_password')??''}}</span>
                                                                                    <span style="color: red">{{session('old_password')??''}}</span>
                                                                                </div>

                                                                                <div class="form-group">
                                                                                    <label class="control-label">{{__('dashboard.New Login Password')}}
                                                                                    </label>
                                                                                    <input type="password"
                                                                                           class="form-control"
                                                                                           name="new_password">
                                                                                    <span style="color: red">{{$errors->first('new_password')??''}}</span>
                                                                                    <span style="color: red">{{session('new_password')??''}}</span>
                                                                                </div>

                                                                                <div class="form-group">
                                                                                    <label class="control-label">{{__('dashboard.Retype Login Password')}}
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
            $('#dob').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                // startDate: moment().format('DD-MM') + '-' + Number(moment().format('YYYY') - 22),
                // maxDate: moment().format('DD-MM') + '-' + Number(moment().format('YYYY') - 18),
                locale: {
                    format: 'DD-MM-YYYY',
                },
            }, function (start, end, label) {
                var years = moment().diff(start, 'years');
                if (years < 18) {
                    swal("You must be at least 18 years old.");
                }
            });
        </script>
@stop